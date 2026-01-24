<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('absensi_gurus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lembaga_id')->constrained()->cascadeOnDelete();
            $table->foreignId('guru_id')->constrained()->cascadeOnDelete();
            $table->foreignId('jadwal_id')->nullable()->constrained()->nullOnDelete();

            $table->date('tanggal');
            $table->dateTime('waktu_scan')->nullable();

            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->integer('jarak_meter')->nullable();

            $table->boolean('radius_valid')->default(false);

            $table->enum('metode', ['qr', 'manual'])->default('qr');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpha'])->default('hadir');
            $table->enum('status_masuk', ['belum_waktu', 'tepat_waktu','terlambat'])->nullable();

            $table->text('keterangan')->nullable();

            $table->timestamps();

            $table->unique(['lembaga_id', 'guru_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_gurus');
    }
};
