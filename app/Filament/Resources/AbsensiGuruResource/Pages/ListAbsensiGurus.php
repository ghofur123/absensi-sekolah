<?php

namespace App\Filament\Resources\AbsensiGuruResource\Pages;

use App\Filament\Resources\AbsensiGuruResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListAbsensiGurus extends ListRecords
{
    protected static string $resource = AbsensiGuruResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),

            Action::make('scan_qr')
                ->label('Scan QR Absen Guru')
                ->icon('heroicon-o-qr-code')
                ->color('success')
                ->url(route('scan.guru')) // route web tadi
                ->openUrlInNewTab(),
        ];
    }
}
