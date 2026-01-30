<?php

namespace App\Filament\Widgets;

use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Lembaga;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Widgets\ChartWidget;

class AbsensiPerKelasChart extends ChartWidget implements HasForms
{
    use InteractsWithForms;

    public ?int $jadwal_id = null; // public property untuk filter jadwal

    public function getColumnSpan(): int|string|array
    {
        return 12; // full width
    }

    public function getHeading(): ?string
    {
        return 'Perbandingan Absensi Per Kelas';
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('jadwal_id')
                ->label('Pilih Jadwal')
                ->options(
                    Jadwal::with('mataPelajaran', 'guru')->get()->mapWithKeys(fn($j) => [
                        $j->id => $j->mataPelajaran->nama . ' - ' . $j->guru->nama
                    ])->toArray()
                )
                ->reactive(), // wajib supaya chart refresh
        ];
    }

    protected function getData(): array
    {
        $labels = [];
        $hadir = [];
        $izin  = [];
        $sakit = [];
        $alpha = [];

        $lembagas = Lembaga::with('kelas.siswas.absensis')->get();

        foreach ($lembagas as $lembaga) {
            foreach ($lembaga->kelas as $kelas) {

                // Ambil semua absensi siswa
                $absensis = $kelas->siswas->flatMap(fn($siswa) => $siswa->absensis);

                // Filter per jadwal jika ada
                if ($this->jadwal_id) {
                    $absensis = $absensis->where('jadwal_id', $this->jadwal_id);
                }

                $labels[] = $lembaga->nama_lembaga . ' - ' . $kelas->nama_kelas;

                $hadir[] = $absensis->where('status', 'hadir')->count();
                $izin[]  = $absensis->where('status', 'izin')->count();
                $sakit[] = $absensis->where('status', 'sakit')->count();
                $alpha[] = $absensis->where('status', 'alpha')->count();
            }
        }

        return [
            'labels' => $labels,
            'datasets' => [
                ['label' => 'Hadir', 'data' => $hadir],
                ['label' => 'Izin',  'data' => $izin],
                ['label' => 'Sakit', 'data' => $sakit],
                ['label' => 'Alpha', 'data' => $alpha],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
