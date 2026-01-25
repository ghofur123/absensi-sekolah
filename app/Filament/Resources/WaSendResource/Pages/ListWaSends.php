<?php

namespace App\Filament\Resources\WaSendResource\Pages;

use App\Filament\Resources\WaSendResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWaSends extends ListRecords
{
    protected static string $resource = WaSendResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
