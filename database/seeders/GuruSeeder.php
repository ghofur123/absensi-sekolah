<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lembagaIds = DB::table('lembagas')->pluck('id');

        foreach ($lembagaIds as $index => $lembagaId) {
            DB::table('gurus')->insert([
                'lembaga_id' => $lembagaId,
                'user_id' => null,
                'nama' => "Guru " . ($index + 1),
                'nik' => '1970' . str_pad($index + 1, 6, '0', STR_PAD_LEFT),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
