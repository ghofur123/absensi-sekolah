<?php

namespace App\Filament\Widgets;

use App\Models\Siswa;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SiswaBerisikoWidget extends BaseWidget
{
    protected static string $view = 'filament.widgets.siswa-berisiko';

    protected int|string|array $columnSpan = 'full';

    public function getViewData(): array
    {
        $from = Carbon::today()->subDays(7);

        return [
            'siswa' => Siswa::whereHas('absensis', function ($q) use ($from) {
                $q->whereDate('tanggal', '>=', $from)
                    ->where('status', 'alpha');
            }, '>=', 3)->get(),
        ];
    }
}
