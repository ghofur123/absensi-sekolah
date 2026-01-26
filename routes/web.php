<?php

use App\Http\Controllers\AbsensiGuruQr;
use App\Http\Controllers\KartuQrAbsensiGuruController;
use App\Http\Controllers\KartuSiswaController;
use App\Http\Controllers\ScanAbsensiController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\WhatsappController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

Route::get('/', function () {
    return view('welcome');
});

// Livewire::setUpdateRoute(function ($handle) {
//     return Route::post('./sodaqoh/absensi-sekolah/public/livewire/update', $handle);
// });
// Livewire::setScriptRoute(function ($handle) {
//     return Route::get('./sodaqoh/absensi-sekolah/livewire/livewire.js', $handle);
// });
Route::get('/run-artisan', function () {
    // Clear dan cache config
    // Artisan::call('config:clear');
    // Artisan::call('config:cache');

    // // Clear dan cache route
    // Artisan::call('route:clear');
    // Artisan::call('route:cache');

    // // Clear dan cache views
    // Artisan::call('view:clear');
    // Artisan::call('view:cache');

    // // Jalankan migrasi database
    // Artisan::call('migrate', ['--force' => true]);

    // // Cache ulang komponen Filament
    // Artisan::call('filament:cache-components');

    // // Jika perlu, buat ulang storage symlink
    // Artisan::call('storage:link');

    return '✅ Semua perintah Artisan berhasil dijalankan!';
});
Route::get('/kirim-wa', [WhatsappController::class, 'kirim']);
Route::get('/download-template/guru', function () {
    $path = public_path('templates/guru.xlsx');

    abort_unless(file_exists($path), 404);

    return Response::download($path, 'guru.xlsx');
});
Route::get('/download-template/siswa', function () {
    $path = public_path('templates/siswa.xlsx');

    abort_unless(file_exists($path), 404);

    return Response::download($path, 'siswa.xlsx');
});
Route::get('/kelas/{kelas}/kartu-siswa-pdf', [KartuSiswaController::class, 'pdf'])
    ->name('kelas.kartu.pdf');
Route::get(
    '/qr/absensi-guru/jadwal/{jadwal}',
    [KartuQrAbsensiGuruController::class, 'pdf']
)->name('qr.absen.guru.pdf');

Route::middleware('auth')->group(function () {
    // scan siswa
    Route::get(
        '/scan/jadwal/{jadwal}',
        [ScanAbsensiController::class, 'index']
    )->name('scan.jadwal');
    Route::post(
        '/scan/jadwal/{jadwal}',
        [ScanAbsensiController::class, 'store']
    )->name('scan.jadwal.store');
    Route::get('/scan-auto', [ScanAbsensiController::class, 'scanAuto'])
        ->name('scan.auto'); // nama route

    // scan guru
    Route::get('/scan-guru', [AbsensiGuruQr::class, 'index'])
        ->name('scan.guru');

    Route::post('/scan/guru', [AbsensiGuruQr::class, 'scan'])
        ->name('scan.guru.store');
});
