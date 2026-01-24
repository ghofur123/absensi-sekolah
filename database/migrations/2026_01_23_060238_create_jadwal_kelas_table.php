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
        Schema::create('jadwal_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')
                ->constrained('jadwals')
                ->onDelete('cascade');
            $table->foreignId('kelas_id')
                ->constrained('kelas')
                ->onDelete('cascade');
            $table->timestamps();

            // Unique constraint untuk mencegah duplikasi
            $table->unique(['jadwal_id', 'kelas_id']);

            // Index untuk performa query
            $table->index('jadwal_id');
            $table->index('kelas_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_kelas');
    }
};
