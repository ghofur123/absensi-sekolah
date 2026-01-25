<?php

namespace App\Filament\Resources\LembagaSettingResource\Pages;

use App\Filament\Resources\LembagaSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLembagaSetting extends EditRecord
{
    protected static string $resource = LembagaSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
