<?php

namespace App\Filament\Resources\AbsensiResource\Pages;

use App\Filament\Resources\AbsensiResource;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\User;
use CCK\FilamentQrcodeScannerHtml5\BarcodeScannerHeaderAction;
use CCK\FilamentQrcodeScannerHtml5\Enums\BarcodeFormat;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListAbsensis extends ListRecords
{
    protected static string $resource = AbsensiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // BarcodeScannerHeaderAction::make()
            //     ->label('Scan QR Siswa')
            //     ->afterScan(function (string $value) {

            //         $siswa = Siswa::find($value);

            //         if (! $siswa) {
            //             Notification::make()
            //                 ->title('QR tidak valid')
            //                 ->danger()
            //                 ->send();
            //             return;
            //         }

            //         $sudahAbsen = Absensi::where('siswa_id', $siswa->id)
            //             ->whereDate('created_at', today())
            //             ->exists();

            //         if ($sudahAbsen) {
            //             Notification::make()
            //                 ->title('Sudah Absen')
            //                 ->warning()
            //                 ->body($siswa->nama_siswa . ' sudah absen hari ini')
            //                 ->send();
            //             return;
            //         }

            //         Absensi::create([
            //             'siswa_id' => $siswa->id,
            //             'diabsenkan_oleh_user_id' => auth()->id(),
            //             'status' => 'hadir',
            //             'waktu_scan' => now(),
            //         ]);

            //         Notification::make()
            //             ->title('Berhasil')
            //             ->success()
            //             ->body('Absensi ' . $siswa->nama_siswa . ' berhasil disimpan')
            //             ->send();
            //     })


        ];
    }
}
