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
            
        ];
    }
    // Tambahkan listener
    // protected $listeners = ['simpanAbsensi'];

    // public function simpanAbsensi($jadwalId, $kode)
    // {
    //     $siswa = Siswa::where('qr_code', $kode)->first();

    //     if (!$siswa) {
    //         Notification::make()
    //             ->title('Gagal!')
    //             ->danger()
    //             ->body('QR Code tidak valid: ' . $kode)
    //             ->send();
    //         return;
    //     }

    //     $sudahAbsen = Absensi::where('siswa_id', $siswa->id)
    //         ->where('jadwal_id', $jadwalId)
    //         ->whereDate('created_at', today())
    //         ->exists();

    //     if ($sudahAbsen) {
    //         Notification::make()
    //             ->title('Perhatian!')
    //             ->warning()
    //             ->body($siswa->nama_siswa . ' sudah absen hari ini.')
    //             ->send();
    //         return;
    //     }

    //     Absensi::create([
    //         'siswa_id' => $siswa->id,
    //         'jadwal_id' => $jadwalId,
    //         'diabsenkan_oleh_user_id' => auth()->id(),
    //         'status' => 'hadir',
    //         'waktu_scan' => now(),
    //     ]);

    //     Notification::make()
    //         ->title('Berhasil!')
    //         ->success()
    //         ->body('Absensi ' . $siswa->nama_siswa . ' berhasil disimpan.')
    //         ->send();
    // }
}
