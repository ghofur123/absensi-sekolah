<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelasList = Kelas::all();

        foreach ($kelasList as $kelas) {
            for ($i = 1; $i <= 10; $i++) {
                Siswa::create([
                    'lembaga_id' => $kelas->lembaga_id,
                    'kelas_id'   => $kelas->id,
                    'nisn'       => $kelas->id . sprintf('%03d', $i),
                    'nama_siswa' => 'Siswa ' . $i . ' ' . $kelas->nama_kelas,
                    'jenis_kelamin' => $i % 2 === 0 ? 'Perempuan' : 'Laki-laki',
                    'alamat'     => 'Alamat Siswa ' . $i,
                    'status'     => 'aktif',
                    'no_wa'      => '08' . rand(1111111111, 9999999999),
                ]);
            }
        }
    }
}
