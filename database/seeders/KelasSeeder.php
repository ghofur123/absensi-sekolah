<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lembagaIds = DB::table('lembagas')->pluck('id');

        foreach ($lembagaIds as $index => $lembagaId) {
            DB::table('kelas')->insert([
                'lembaga_id' => $lembagaId,
                'nama_kelas' => 'Kelas ' . ($index + 1),
                'tingkat' => rand(1, 12),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
