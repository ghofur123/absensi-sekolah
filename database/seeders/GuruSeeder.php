<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $lembagaIds = DB::table('lembagas')->pluck('id');

        foreach ($lembagaIds as $lembagaId) {

            // misal: 5 guru per lembaga
            for ($i = 1; $i <= 5; $i++) {
                DB::table('gurus')->insert([
                    'lembaga_id' => $lembagaId,
                    'user_id'    => null,
                    'nama'       => $faker->name,
                    'nik'        => $faker->unique()->numerify('1970######'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
