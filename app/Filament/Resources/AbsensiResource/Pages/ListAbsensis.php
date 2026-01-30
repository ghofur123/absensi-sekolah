<?php

namespace App\Filament\Resources\AbsensiResource\Pages;

use App\Filament\Resources\AbsensiResource;
use App\Filament\Widgets\AbsensiPerKelasChart;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;


class ListAbsensis extends ListRecords
{
    protected static string $resource = AbsensiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Action::make('scan_qr')
            //     ->label('Scan QR')
            //     ->icon('heroicon-o-qr-code')
            //     ->color('success')
            //     ->url(route('scan.auto')) // route web tadi
            //     ->openUrlInNewTab(),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            // AbsensiPerKelasChart::class,
        ];
    }
}
