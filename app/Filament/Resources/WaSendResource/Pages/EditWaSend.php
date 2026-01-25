<?php

namespace App\Filament\Resources\WaSendResource\Pages;

use App\Filament\Resources\WaSendResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWaSend extends EditRecord
{
    protected static string $resource = WaSendResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
