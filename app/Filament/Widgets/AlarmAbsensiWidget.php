<?php

namespace App\Filament\Widgets;

use App\Models\Jadwal;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AlarmAbsensiWidget extends BaseWidget
{
    protected static string $view = 'filament.widgets.alarm-absensi';

    protected int|string|array $columnSpan = 'full';

    public function getViewData(): array
    {
        $hariIni = now()->locale('id')->dayName;

        return [
            'jadwalBelumAbsen' => Jadwal::whereJsonContains('hari', $hariIni)
                ->whereDoesntHave('absensis', function ($q) {
                    $q->whereDate('tanggal', Carbon::today());
                })
                ->get(),
        ];
    }
}
