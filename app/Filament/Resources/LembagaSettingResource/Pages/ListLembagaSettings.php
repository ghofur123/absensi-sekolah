<?php

namespace App\Filament\Resources\LembagaSettingResource\Pages;

use App\Filament\Resources\LembagaSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLembagaSettings extends ListRecords
{
    protected static string $resource = LembagaSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
