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
        Schema::create('lembaga_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lembaga_id')->constrained()->cascadeOnDelete();

            // Global WA
            $table->boolean('wa_absensi_enabled')->default(false);
            $table->string('fonnte_token')->nullable();

            // Status absensi (ON / OFF)
            $table->boolean('kirim_hadir')->default(true);
            $table->boolean('kirim_izin')->default(true);
            $table->boolean('kirim_sakit')->default(true);
            $table->boolean('kirim_alpa')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lembaga_settings');
    }
};
