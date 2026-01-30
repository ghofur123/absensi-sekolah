<?php

namespace App\Filament\Pages;

use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Lembaga;
use App\Models\Siswa;
use Filament\Pages\Page;

class LaporanAbsensi extends Page
{
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Laporan Absensi';
    protected static string $view = 'filament.resources.laporan-absensi-resource.pages.laporan-absensi';

    // FILTER
    public ?int $lembagaId = null;
    public ?int $kelasId = null;
    public ?string $tanggalAwal = null;
    public ?string $tanggalAkhir = null;

    // DATA
    public $lembagas = [];
    public $kelas = [];
    public $absensi = [];

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
        $this->absensi = collect();
    }

    public function updatedKelasId($value): void
    {
        $this->loadAbsensi();
    }

    public function loadAbsensi(): void
    {
        if (
            !$this->lembagaId ||
            !$this->kelasId ||
            !$this->tanggalAwal ||
            !$this->tanggalAkhir
        ) {
            $this->absensi = collect();
            return;
        }

        $siswa = Siswa::where('lembaga_id', $this->lembagaId)
            ->where('kelas_id', $this->kelasId)
            ->get();

        $absensi = Absensi::whereHas('siswa', function ($q) {
            $q->where('lembaga_id', $this->lembagaId)
                ->where('kelas_id', $this->kelasId);
        })
            ->whereBetween('waktu_scan', [
                $this->tanggalAwal . ' 00:00:00',
                $this->tanggalAkhir . ' 23:59:59',
            ])
            ->get()
            ->groupBy('siswa_id');

        $this->absensi = $siswa->map(function ($s) use ($absensi) {

            $data = $absensi->get($s->id, collect());

            return (object) [
                'siswa'        => $s,
                'hadir'        => $data->where('status', 'Hadir')->count(),
                'sakit'        => $data->where('status', 'Sakit')->count(),
                'izin'         => $data->where('status', 'Izin')->count(),
                'tidak_hadir'  => $data->where('status', 'Tidak Hadir')->count()
                    + ($data->isEmpty() ? 1 : 0),
            ];
        });
    }
}
