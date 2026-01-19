<?php

namespace App\Filament\Resources\JadwalResource\Pages;

use App\Filament\Resources\JadwalResource;
use App\Models\Absensi;
use App\Models\Siswa;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJadwals extends ListRecords
{
    protected static string $resource = JadwalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function simpanAbsensi($data)
    {
        $siswa = Siswa::where('qr_code', $data['kode'])->first();

        if (!$siswa) {
            return;
        }

        if (Absensi::where('siswa_id', $siswa->id)
            ->where('jadwal_id', $data['jadwal_id'])
            ->whereDate('created_at', today())
            ->exists()
        ) {
            return;
        }

        Absensi::create([
            'siswa_id' => $siswa->id,
            'jadwal_id' => $data['jadwal_id'],
            'diabsenkan_oleh_user_id' => auth()->user()->id,
            'status' => 'hadir',
            'waktu_scan' => now(),
        ]);
    }
}
