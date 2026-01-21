<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // aman jika seed ulang
        // DB::table('jadwals')->truncate();

        $kelas = DB::table('kelas')->get();

        $hariList = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];

        // ambil 1 guru & mapel (jika wajib)
        $guruId  = DB::table('gurus')->value('id');
        $mapelId = DB::table('mata_pelajarans')->value('id');

        foreach ($kelas as $kls) {
            foreach ($hariList as $hari) {
                DB::table('jadwals')->insert([
                    'lembaga_id' => $kls->lembaga_id,
                    'kelas_id'   => $kls->id,

                    'guru_id' => $guruId,
                    'mata_pelajaran_id' => $mapelId,

                    'hari' => $hari,
                    'jam_mulai' => '07:00:00',
                    'jam_selesai' => '08:00:00',

                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
