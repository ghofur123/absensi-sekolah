<?php

namespace Database\Seeders;

use App\Models\Lembaga;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MataPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'Matematika',
            'Bahasa Indonesia',
            'Bahasa Inggris',
            'IPA',
            'IPS',
            'PAI',
            'PKn',
            'PJOK',
            'SBK',
            'Informatika',
        ];

        $lembagas = Lembaga::all();

        foreach ($data as $nama) {
            foreach ($lembagas as $lembaga) {  // looping per lembaga
                DB::table('mata_pelajarans')->insert([
                    'nama_mapel' => $nama,
                    'lembaga_id' => $lembaga->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
