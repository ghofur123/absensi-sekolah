<?php

namespace App\Filament\Resources\QrScanerResource\Pages;

use App\Filament\Resources\QrScanerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQrScaner extends EditRecord
{
    protected static string $resource = QrScanerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
