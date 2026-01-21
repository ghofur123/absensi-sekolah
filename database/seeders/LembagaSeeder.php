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
        DB::table('lembagas')->insert([
            [
                'nama_lembaga' => 'MTS NURUL MANNAN',
                'alamat' => 'Jl. Pasar Jum at NO 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_lembaga' => 'SMK NURUL MANNAN',
                'alamat' => 'Jl. Pasar Jum at NO 2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
