<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')
                ->nullable()
                ->constrained('siswas')
                ->nullOnDelete();

            $table->foreignId('jadwal_id')
                ->nullable()
                ->constrained('jadwals')
                ->nullOnDelete();

            $table->foreignId('diabsenkan_oleh_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpa'])->default('alpa');

            $table->dateTime('waktu_scan')->nullable();
            $table->date('tanggal');
            $table->timestamps();
            // Optional index untuk performa
            $table->unique(['siswa_id', 'jadwal_id', 'tanggal'], 'unique_absensi_harian');
        });
        DB::statement('ALTER TABLE absensis MODIFY tanggal DATE NOT NULL DEFAULT (CURDATE())');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
