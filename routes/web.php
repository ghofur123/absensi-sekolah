<?php

use App\Http\Controllers\WebhookController;
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
//     return Route::get('./sodaqoh/absensi-sekolah/public/livewire/livewire.js', $handle);
// });
Route::get('/run-artisan', function () {
    // Clear dan cache config
    Artisan::call('config:clear');
    Artisan::call('config:cache');

    // Clear dan cache route
    Artisan::call('route:clear');
    Artisan::call('route:cache');

    // Clear dan cache views
    Artisan::call('view:clear');
    Artisan::call('view:cache');

    // Jalankan migrasi database
    Artisan::call('migrate', ['--force' => true]);

    // Cache ulang komponen Filament
    Artisan::call('filament:cache-components');

    // Jika perlu, buat ulang storage symlink
    Artisan::call('storage:link');

    return '✅ Semua perintah Artisan berhasil dijalankan!';
});
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
