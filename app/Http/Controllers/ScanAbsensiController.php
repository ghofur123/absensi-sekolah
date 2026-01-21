<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScanAbsensiController extends Controller
{
    public function index(Jadwal $jadwal)
    {
        return view('scan.scan-jadwal', compact('jadwal'));
    }

    public function store(Request $request, Jadwal $jadwal)
    {
        try {
            $request->validate(['qr' => 'required']);

            // HARD VALIDATION KELAS
            $siswa = Siswa::where('id', $request->qr)
                ->where('kelas_id', $jadwal->kelas_id)
                ->first();

            if (! $siswa) {
                return response()->json([
                    'status' => 'error',
                    'message' => '❌ Siswa bukan dari kelas ini'
                ]);
            }

            // ANTI DUPLIKAT (HARI INI)
            $absensi = Absensi::where('jadwal_id', $jadwal->id)
                ->where('siswa_id', $siswa->id)
                ->whereDate('created_at', today())
                ->first();

            if ($absensi) {
                return response()->json([
                    'status' => 'error',
                    'message' => '⚠️ Siswa sudah absen'
                ]);
            }

            Absensi::create([
                'jadwal_id' => $jadwal->id,
                'siswa_id' => $siswa->id,
                'status' => 'hadir',
                'waktu_scan' => now(),
                'diabsenkan_oleh_user_id' => auth()->id(),
            ]);

            return response()->json([
                'status' => 'success',
                'nama' => $siswa->nama_siswa,
                'kelas' => $siswa->kelas_id,
                'mode' => 'insert',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
