<?php

namespace App\Filament\Resources\JadwalResource\Pages;

use App\Filament\Resources\JadwalResource;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\User;
use CCK\FilamentQrcodeScannerHtml5\BarcodeScannerHeaderAction;
use CCK\FilamentQrcodeScannerHtml5\Enums\BarcodeFormat;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListJadwals extends ListRecords
{
    protected static string $resource = JadwalResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
