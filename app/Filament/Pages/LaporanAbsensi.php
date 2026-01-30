<?php

namespace App\Filament\Pages;

use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Lembaga;
use App\Models\MataPelajaran;
use App\Models\Siswa;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Pages\Page;

class LaporanAbsensi extends Page
{
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Rekap Absensi';
    protected static string $view = 'filament.resources.laporan-absensi-resource.pages.laporan-absensi';

    // FILTER
    public ?int $lembagaId = null;
    public ?int $kelasId = null;
    public ?string $tanggalAwal = null;
    public ?string $tanggalAkhir = null;
    public ?int $mataPelajaranId = null;

    // DATA
    public $lembagas = [];
    public $kelas = [];
    public $absensi = [];
    public $mata_pelajaran = [];

    public function mount(): void
    {
        $this->lembagas = Lembaga::orderBy('nama_lembaga')->get();
        $this->kelas = collect();
        $this->absensi = collect();
    }

    public function updatedLembagaId($value): void
    {
        $this->kelasId = null;
        $this->kelas = Kelas::where('lembaga_id', $value)->get();
        $this->mata_pelajaran = MataPelajaran::where('lembaga_id', $value)->get();
        $this->absensi = collect();
    }

    public function updatedTanggalAwal(): void
    {
        $this->loadAbsensi();
    }

    public function updatedTanggalAkhir(): void
    {
        $this->loadAbsensi();
    }

    public function updatedKelasId(): void
    {
        $this->loadAbsensi();
    }

    public function updatedMataPelajaranId(): void
    {
        $this->loadAbsensi();
    }

    public function loadAbsensi(): void
    {
        if (
            !$this->lembagaId ||
            !$this->kelasId ||
            !$this->tanggalAwal ||
            !$this->tanggalAkhir ||
            !$this->mataPelajaranId
        ) {
            $this->absensi = collect();
            return;
        }

        // Ambil siswa di kelas dan lembaga
        $siswa = Siswa::where('lembaga_id', $this->lembagaId)
            ->where('kelas_id', $this->kelasId)
            ->get();

        // Ambil absensi siswa untuk rentang tanggal dan mata pelajaran
        $absensi = Absensi::whereHas('siswa', function ($q) {
            $q->where('lembaga_id', $this->lembagaId)
                ->where('kelas_id', $this->kelasId);
        })
            ->whereHas('jadwal', function ($q) {
                $q->where('mata_pelajaran_id', $this->mataPelajaranId);
            })
            ->whereBetween('waktu_scan', [
                $this->tanggalAwal . ' 00:00:00',
                $this->tanggalAkhir . ' 23:59:59',
            ])
            ->get()
            ->groupBy('siswa_id');

        // Ambil tanggal unik dari absensi (hanya hari ada kegiatan)
        $tanggalAbsensi = $absensi->flatMap(
            fn($a) =>
            $a->pluck('waktu_scan')->map(fn($waktu) => Carbon::parse($waktu)->format('Y-m-d'))
        )->unique()->toArray();

        $this->absensi = $siswa->map(function ($s) use ($absensi, $tanggalAbsensi) {

            $data = $absensi->get($s->id, collect());

            $hadir = $data->where('status', 'hadir')->count();
            $izin  = $data->where('status', 'izin')->count();
            $sakit = $data->where('status', 'sakit')->count();

            $sudahAbsen = $hadir + $izin + $sakit;

            // Total hari diambil dari tanggal yang benar-benar ada absensi
            $totalHari = count($tanggalAbsensi);
            $alpa = max($totalHari - $sudahAbsen, 0);

            $persentase = $totalHari > 0
                ? round(($hadir / $totalHari) * 100, 1)
                : 0;

            $keterangan = match (true) {
                $persentase >= 95 => 'Sangat Baik',
                $persentase >= 85 => 'Baik',
                $persentase >= 75 => 'Cukup',
                $persentase >= 60 => 'Kurang',
                default           => 'Perlu Perhatian',
            };

            return (object) [
                'siswa' => $s,
                'hadir' => $hadir,
                'izin'  => $izin,
                'sakit' => $sakit,
                'alpa'  => $alpa,
                'persentase' => $persentase,
                'keterangan' => $keterangan,
            ];
        });
    }
}
