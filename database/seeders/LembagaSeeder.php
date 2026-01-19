<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LembagaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('lembagas')->insert([
                'nama_lembaga' => "Lembaga $i",
                'alamat' => "Alamat $i",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
