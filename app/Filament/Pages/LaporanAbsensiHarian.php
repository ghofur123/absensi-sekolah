<?php

namespace App\Filament\Pages;

use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Lembaga;
use App\Models\MataPelajaran;
use App\Models\Siswa;
use App\Support\AbsensiStatusHelper;
use Carbon\Carbon;
use Filament\Pages\Page;

class LaporanAbsensiHarian extends Page
{
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Rekap Absensi Harian';

    protected static string $view =
    'filament.resources.laporan-absensi-harian-resource.pages.laporan-absensi-harian';

    // FILTER
    public ?int $lembagaId = null;
    public ?int $kelasId = null;
    public ?string $tanggalAwal = null;
    public ?string $tanggalAkhir = null;
    public ?int $mataPelajaranId = null;
    public ?string $status = null;

    // DATA — semuanya plain array untuk Livewire serialization
    public array $lembagas = [];
    public array $kelas = [];
    public array $mata_pelajaran = [];
    public array $absensi = [];

    public function mount(): void
    {
        // Konversi ke plain array dari awal
        $this->lembagas = Lembaga::orderBy('nama_lembaga')
            ->get()
            ->map(fn($l) => ['id' => $l->id, 'nama' => $l->nama_lembaga])
            ->toArray();
    }

    public function updatedLembagaId($value): void
    {
        $this->kelasId = null;
        $this->mataPelajaranId = null;
        $this->tanggalAwal = null;
        $this->tanggalAkhir = null;
        $this->status = null;

        $this->kelas = Kelas::where('lembaga_id', $value)
            ->get()
            ->map(fn($k) => ['id' => $k->id, 'nama' => $k->nama_kelas])
            ->toArray();

        $this->mata_pelajaran = MataPelajaran::where('lembaga_id', $value)
            ->get()
            ->map(fn($m) => ['id' => $m->id, 'nama' => $m->nama_mapel])
            ->toArray();

        $this->absensi = [];
    }

    public function updatedKelasId(): void
    {
        $this->loadAbsensi();
    }

    public function updatedTanggalAwal(): void
    {
        $this->loadAbsensi();
    }

    public function updatedTanggalAkhir(): void
    {
        $this->loadAbsensi();
    }

    public function updatedMataPelajaranId(): void
    {
        $this->loadAbsensi();
    }

    public function updatedStatus(): void
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
            $this->absensi = [];
            return;
        }

        // =========================
        // SISWA
        // =========================
        $siswa = Siswa::where('lembaga_id', $this->lembagaId)
            ->where('kelas_id', $this->kelasId)
            ->get();

        // =========================
        // ABSENSI
        // =========================
        $absensiData = Absensi::with('jadwal')
            ->whereHas('siswa', function ($q) {
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
            ->get();

        $absensiGrouped = $absensiData->groupBy('siswa_id');

        // =========================
        // TANGGAL UNIK
        // =========================
        $tanggalAbsensi = $absensiData
            ->pluck('waktu_scan')
            ->map(fn($waktu) => Carbon::parse($waktu)->format('Y-m-d'))
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        if (empty($tanggalAbsensi)) {
            $this->absensi = [];
            return;
        }

        $totalHari = count($tanggalAbsensi);

        // =========================
        // PROSES DATA PER SISWA
        // =========================
        $rows = [];

        foreach ($siswa as $s) {
            $dataSiswa = $absensiGrouped->get($s->id, collect());

            // --- Resolusi status per hari ---
            $statusPerHari = [];

            foreach ($tanggalAbsensi as $tgl) {
                $absensiHari = $dataSiswa->filter(
                    fn($a) => Carbon::parse($a->waktu_scan)->format('Y-m-d') === $tgl
                );

                if ($absensiHari->isEmpty()) {
                    $statusPerHari[$tgl] = [
                        'status' => 'alpa',
                        'kode'   => 'A',
                        'text'   => 'Alpa',
                    ];
                    continue;
                }

                // Prioritas: hadir > izin > sakit
                $terpilih = null;
                foreach (['hadir', 'izin', 'sakit'] as $p) {
                    $found = $absensiHari->first(fn($a) => $a->status === $p);
                    if ($found) {
                        $terpilih = $found;
                        break;
                    }
                }
                if (!$terpilih) {
                    $terpilih = $absensiHari->first();
                }

                $kode = '-';
                $text = '';

                if ($terpilih->status === 'izin') {
                    $kode = 'I';
                    $text = 'Izin';
                } elseif ($terpilih->status === 'sakit') {
                    $kode = 'S';
                    $text = 'Sakit';
                } elseif ($terpilih->status === 'hadir') {
                    $statusMasuk = AbsensiStatusHelper::statusMasuk($terpilih);
                    $kode = AbsensiStatusHelper::kode($statusMasuk, $terpilih->status);
                    $text = $statusMasuk ?? 'Hadir';
                } else {
                    $text = ucfirst($terpilih->status ?? 'Unknown');
                }

                $statusPerHari[$tgl] = [
                    'status' => $terpilih->status,
                    'kode'   => $kode,
                    'text'   => $text,
                ];
            }

            // --- Statistik ---
            $hadir      = 0;
            $izin       = 0;
            $sakit      = 0;
            $alpa       = 0;
            $hadirResmi = 0;

            foreach ($statusPerHari as $info) {
                switch ($info['status']) {
                    case 'hadir':
                        $hadir++;
                        if ($info['kode'] === 'H') $hadirResmi++;
                        break;
                    case 'izin':
                        $izin++;
                        break;
                    case 'sakit':
                        $sakit++;
                        break;
                    default:
                        $alpa++;
                        break;
                }
            }

            $persentase = $totalHari > 0
                ? round(($hadirResmi / $totalHari) * 100, 1)
                : 0;

            $keterangan = match (true) {
                $persentase >= 95 => 'Sangat Baik',
                $persentase >= 85 => 'Baik',
                $persentase >= 75 => 'Cukup',
                $persentase >= 60 => 'Kurang',
                default           => 'Perlu Perhatian',
            };

            // --- Kolom harian (plain indexed array) ---
            $harian = [];
            foreach ($tanggalAbsensi as $tgl) {
                $info = $statusPerHari[$tgl];

                if ($this->status && $info['status'] !== $this->status) {
                    $harian[] = ['kode' => '-', 'text' => ''];
                } else {
                    $harian[] = ['kode' => $info['kode'], 'text' => $info['text']];
                }
            }

            $rows[] = [
                'nama'       => $s->nama_siswa,
                'harian'     => $harian,
                'hadir'      => $hadir,
                'izin'       => $izin,
                'sakit'      => $sakit,
                'alpa'       => $alpa,
                'persentase' => $persentase,
                'keterangan' => $keterangan,
            ];
        }

        // =========================
        // FINAL — semuanya plain array
        // =========================
        $this->absensi = [
            'tanggal' => $tanggalAbsensi,
            'data'    => $rows,
        ];
    }
}
