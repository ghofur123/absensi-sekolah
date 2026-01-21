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
        // kalau sering migrate:fresh
        // DB::table('kelas')->delete();

        $lembagaIds = DB::table('lembagas')->pluck('id');

        foreach ($lembagaIds as $lembagaId) {

            for ($i = 1; $i <= 3; $i++) {
                DB::table('kelas')->insert([
                    'lembaga_id' => $lembagaId,
                    'nama_kelas' => 'Kelas ' . $i,
                    'tingkat' => $i, // bisa 1,2,3 (lebih masuk akal)
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
