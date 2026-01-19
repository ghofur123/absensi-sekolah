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
        $kelas = DB::table('kelas')->get();
        $guru  = DB::table('gurus')->pluck('id');
        $mapel = DB::table('mata_pelajarans')->pluck('id');

        $hari = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
        $jam  = [
            ['07:00', '08:00'],
            ['08:00', '09:00'],
            ['09:00', '10:00'],
            ['10:00', '11:00'],
        ];

        $i = 0;

        foreach ($kelas as $kls) {
            if ($i >= 10) break;

            DB::table('jadwals')->insert([
                'lembaga_id' => $kls->lembaga_id,
                'kelas_id' => $kls->id,
                'guru_id' => $guru[$i % $guru->count()],
                'mata_pelajaran_id' => $mapel[$i % $mapel->count()],
                'hari' => $hari[$i % count($hari)],
                'jam_mulai' => $jam[$i % count($jam)][0],
                'jam_selesai' => $jam[$i % count($jam)][1],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $i++;
        }
    }
}
