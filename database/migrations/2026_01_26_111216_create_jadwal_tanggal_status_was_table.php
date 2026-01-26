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
        Schema::create('jadwal_tanggal_status_was', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')
                ->constrained('jadwals')
                ->cascadeOnDelete();

            $table->date('tanggal'); // HARI EKSEKUSI JADWAL

            $table->boolean('sudah_dikirim')->default(false);

            $table->timestamp('waktu_kirim')->nullable();

            $table->foreignId('dikirim_oleh_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('keterangan')->nullable();

            $table->timestamps();

            // 🔐 KUNCI UTAMA LOGIKA
            $table->unique(['jadwal_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_tanggal_status_was');
    }
};
