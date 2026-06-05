<?php

namespace App\Filament\Pages;

use App\Models\AbsensiGuru;
use App\Models\Lembaga;
use App\Models\MataPelajaran;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class LaporanAbsensiGuru extends Page
{
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Rekap Absensi Guru';

    protected static string $view =
    'filament.resources.laporan-absensi-guru-resource.pages.laporan-absensi-guru';

    // FILTER
    public ?int $lembagaId = null;
    public ?string $tanggalAwal = null;
    public ?string $tanggalAkhir = null;

    // DATA
    public $lembagas;
    public $mapel;
    public $rekap;

    public function mount(): void
    {
        $this->lembagas = Lembaga::orderBy('nama_lembaga')->get();
        $this->mapel = collect();
        $this->rekap = collect();
    }

    public function updatedLembagaId(): void
    {
        $this->mapel = MataPelajaran::where('lembaga_id', $this->lembagaId)->get();
        $this->loadRekap();
    }

    public function updatedTanggalAwal(): void
    {
        $this->loadRekap();
    }

    public function updatedTanggalAkhir(): void
    {
        $this->loadRekap();
    }

    public function loadRekap(): void
    {
        if (! $this->lembagaId || ! $this->tanggalAwal || ! $this->tanggalAkhir) {
            $this->rekap = collect();
            return;
        }

        // ================= QUERY INTI =================
        $data = AbsensiGuru::query()
            ->where('absensi_gurus.status', 'hadir')
            ->whereBetween('absensi_gurus.waktu_scan', [
                $this->tanggalAwal . ' 00:00:00',
                $this->tanggalAkhir . ' 23:59:59',
            ])
            ->join('gurus', 'gurus.id', '=', 'absensi_gurus.guru_id')
            ->join('jadwals', 'jadwals.id', '=', 'absensi_gurus.jadwal_id')
            ->join('mata_pelajarans', 'mata_pelajarans.id', '=', 'jadwals.mata_pelajaran_id')
            ->where('gurus.lembaga_id', $this->lembagaId) // 🔥 PINDAH KE SINI
            ->select(
                'gurus.id as guru_id',
                'gurus.nama as nama_guru',
                'mata_pelajarans.id as mapel_id',
                DB::raw('COUNT(absensi_gurus.id) as jumlah')
            )
            ->groupBy(
                'gurus.id',
                'gurus.nama',
                'mata_pelajarans.id'
            )
            ->get();


        // ================= BENTUK ULANG =================
        $this->rekap = $data
            ->groupBy('guru_id')
            ->map(function ($items) {
                $row = [
                    'nama' => $items->first()->nama_guru,
                    'total' => 0,
                ];

                // default semua mapel = 0
                foreach ($this->mapel as $m) {
                    $row['mapel_' . $m->id] = 0;
                }

                // isi data aktual
                foreach ($items as $i) {
                    $row['mapel_' . $i->mapel_id] = $i->jumlah;
                    $row['total'] += $i->jumlah;
                }

                return (object) $row;
            })
            ->values();
    }
}
