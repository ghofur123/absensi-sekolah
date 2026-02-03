<?php

namespace App\Filament\Widgets;

use App\Models\Absensi;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class AbsensiMingguanChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Absensi 7 Hari Terakhir';

    protected static ?string $icon = 'heroicon-o-chart-bar';

    public function getColumnSpan(): int|string|array
    {
        return 12; // full width
    }

    protected function getData(): array
    {
        $labels = [];
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('d M');

            $data[] = Absensi::whereDate('tanggal', $date)
                ->where('status', 'hadir')
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Hadir',
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
