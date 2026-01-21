<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $kelasList = Kelas::all();

        foreach ($kelasList as $kelas) {

            for ($i = 1; $i <= 10; $i++) {

                // 🎯 Tentukan gender dulu
                $gender = $faker->randomElement(['Laki-laki', 'Perempuan']);

                Siswa::create([
                    'lembaga_id' => $kelas->lembaga_id,
                    'kelas_id'   => $kelas->id,

                    // 🔢 NISN unik (kelas + urutan)
                    'nisn' => $kelas->id . str_pad($i, 4, '0', STR_PAD_LEFT),

                    // 👤 Nama sesuai gender
                    'nama_siswa' => $gender === 'Laki-laki'
                        ? $faker->firstNameMale . ' ' . $faker->lastName
                        : $faker->firstNameFemale . ' ' . $faker->lastName,

                    'jenis_kelamin' => $gender,

                    // 🏠 Alamat realistis
                    'alamat' => $faker->address,

                    'status' => 'aktif',

                    // 📱 No WA Indonesia (aman)
                    'no_wa' => '08' . $faker->numberBetween(1111111111, 9999999999),
                ]);
            }
        }
    }
}
