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
        Schema::create('wa_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lembaga_id')
                ->constrained()
                ->cascadeOnDelete();

            // ===== WA ORANG TUA =====
            $table->text('header_orang_tua')->nullable();
            $table->text('footer_orang_tua')->nullable();
            $table->boolean('aktif_orang_tua')->default(true);

            // ===== WA GURU =====
            $table->text('header_guru')->nullable();
            $table->text('footer_guru')->nullable();
            $table->boolean('aktif_guru')->default(true);

            $table->timestamps();

            // 1 lembaga = 1 template
            $table->unique('lembaga_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wa_templates');
    }
};
