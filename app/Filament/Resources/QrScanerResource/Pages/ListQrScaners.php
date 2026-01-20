<?php

namespace App\Filament\Resources\QrScanerResource\Pages;

use App\Filament\Resources\QrScanerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQrScaners extends ListRecords
{
    protected static string $resource = QrScanerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
