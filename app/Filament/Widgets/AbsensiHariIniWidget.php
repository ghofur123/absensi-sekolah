<?php

namespace App\Filament\Widgets;

use App\Models\Absensi;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AbsensiHariIniWidget extends BaseWidget
{
    protected function getHeading(): ?string
    {
        return 'Absensi Hari Ini';
    }
    protected function getStats(): array
    {
        $today = Carbon::today();

        return [
            Stat::make('Hadir', Absensi::whereDate('tanggal', $today)->where('status', 'hadir')->count())
                ->color('success'),

            Stat::make('Izin', Absensi::whereDate('tanggal', $today)->where('status', 'izin')->count())
                ->color('warning'),

            Stat::make('Sakit', Absensi::whereDate('tanggal', $today)->where('status', 'sakit')->count())
                ->color('info'),

            Stat::make('Alpha', Absensi::whereDate('tanggal', $today)->where('status', 'alpha')->count())
                ->color('danger'),
        ];
    }
}
