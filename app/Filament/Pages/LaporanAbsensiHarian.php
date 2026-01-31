<?php

namespace App\Filament\Pages;

use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Lembaga;
use App\Models\MataPelajaran;
use App\Models\Siswa;
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

    // DATA
    public $lembagas;
    public $kelas;
    public $mata_pelajaran;
    public $absensi = null;

    public function mount(): void
    {
        $this->lembagas = Lembaga::orderBy('nama_lembaga')->get();
        $this->kelas = collect();
        $this->mata_pelajaran = collect();
    }

    public function updatedLembagaId($value): void
    {
        $this->kelasId = null;
        $this->mataPelajaranId = null;
        $this->tanggalAwal = null;
        $this->tanggalAkhir = null;

        $this->kelas = Kelas::where('lembaga_id', $value)->get();
        $this->mata_pelajaran = MataPelajaran::where('lembaga_id', $value)->get();

        $this->absensi = null;
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
            $this->absensi = null;
            return;
        }

        // =========================
        // SISWA
        // =========================
        $siswa = Siswa::where('lembaga_id', $this->lembagaId)
            ->where('kelas_id', $this->kelasId)
            ->get();

        // =========================
        // ABSENSI (TANPA FILTER STATUS)
        // =========================
        $absensi = Absensi::with('jadwal')
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
            ->get()
            ->groupBy('siswa_id');

        // =========================
        // SEMUA TANGGAL (FULL RANGE)
        // =========================
        $tanggal = collect(
            Carbon::parse($this->tanggalAwal)
                ->daysUntil(Carbon::parse($this->tanggalAkhir))
                ->map(fn($d) => $d->format('Y-m-d'))
        );

        // =========================
        // FINAL DATA
        // =========================
        $this->absensi = [
            'tanggal' => $tanggal,
            'data' => $siswa->map(function ($s) use ($absensi, $tanggal) {

                $dataSiswa = $absensi->get($s->id, collect());

                $harian = $tanggal->mapWithKeys(function ($tgl) use ($dataSiswa) {

                    $absen = $dataSiswa->first(
                        fn($a) => Carbon::parse($a->waktu_scan)->format('Y-m-d') === $tgl
                    );

                    // =========================
                    // TIDAK ADA ABSENSI
                    // =========================
                    if (!$absen) {

                        // ❗ JIKA FILTER STATUS AKTIF → "-"
                        if ($this->status) {
                            return [$tgl => [
                                'kode' => '-',
                                'text' => '',
                            ]];
                        }

                        // ❗ JIKA TIDAK ADA FILTER → ALPA
                        return [$tgl => [
                            'kode' => 'A',
                            'text' => 'Alpa',
                        ]];
                    }

                    // =========================
                    // FILTER STATUS AKTIF
                    // =========================
                    if ($this->status && $absen->status !== $this->status) {
                        return [$tgl => [
                            'kode' => '-',
                            'text' => '',
                        ]];
                    }

                    // =========================
                    // IZIN / SAKIT
                    // =========================
                    if ($absen->status !== 'hadir') {
                        return [$tgl => [
                            'kode' => strtoupper(substr($absen->status, 0, 1)),
                            'text' => ucfirst($absen->status),
                        ]];
                    }

                    // =========================
                    // HADIR (TEPAT / TELAT)
                    // =========================
                    $jamMulai = Carbon::parse($absen->jadwal->jam_mulai);
                    $scan = Carbon::parse($absen->waktu_scan);

                    if ($scan->lte($jamMulai)) {
                        return [$tgl => [
                            'kode' => 'H',
                            'text' => 'Tepat Waktu',
                        ]];
                    }

                    $telat = $jamMulai->diffInMinutes($scan);

                    return [$tgl => [
                        'kode' => 'H',
                        'text' => "Terlambat {$telat} menit",
                    ]];
                });


                return (object) [
                    'siswa' => $s,
                    'harian' => $harian,
                ];
            }),
        ];
    }
}
