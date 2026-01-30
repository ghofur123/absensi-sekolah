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
    // public function scanAuto()
    // {
    //     $hariInggris = Carbon::now()->format('l'); // Monday, Tuesday, ...

    //     $mapHari = [
    //         'Monday' => 'senin',
    //         'Tuesday' => 'selasa',
    //         'Wednesday' => 'rabu',
    //         'Thursday' => 'kamis',
    //         'Friday' => 'jumat',
    //         'Saturday' => 'sabtu',
    //         'Sunday' => 'minggu', // kalau nanti ada
    //     ];

    //     $hariIni = $mapHari[$hariInggris] ?? null;

    //     // Semua jadwal untuk dropdown
    //     $jadwals = Jadwal::all();

    //     // Jadwal hari ini saja
    //     $jadwalsToday = $hariIni ? Jadwal::where('hari', $hariIni)->get() : collect();

    //     return view('scan.scan-auto', compact('jadwals', 'jadwalsToday'));
    // }
    public function store(Request $request, Jadwal $jadwal)
    {
        try {
            $request->validate([
                'qr' => 'required'
            ]);

            // cari siswa
            // $siswa = Siswa::with('kelas')->find($request->qr);
            $siswa = Siswa::with('kelas')
                ->where('id', $request->qr)
                ->where('lembaga_id', $jadwal->lembaga_id)
                ->first();

            if (! $siswa) {
                return response()->json([
                    'status' => 'error',
                    'message' => '❌ Siswa tidak ditemukan atau bukan peserta jadwal ini atau bukan siswa dari lembaga ini'
                ]);
            }

            // 🔒 VALIDASI KELAS VIA PIVOT jadwal_kelas
            $validKelas = $jadwal->kelases()
                ->where('kelas_id', $siswa->kelas_id)
                ->exists();

            if (! $validKelas) {
                return response()->json([
                    'status' => 'error',
                    'message' => '❌ Siswa bukan peserta jadwal ini'
                ]);
            }

            // ⛔ ANTI DUPLIKAT HARI INI
            $sudahAbsen = Absensi::where('jadwal_id', $jadwal->id)
                ->where('siswa_id', $siswa->id)
                ->whereDate('created_at', today())
                ->exists();

            if ($sudahAbsen) {
                return response()->json([
                    'status' => 'error',
                    'message' => '⚠️ Siswa sudah absen hari ini'
                ]);
            }

            // ✅ SIMPAN ABSENSI
            Absensi::create([
                'jadwal_id' => $jadwal->id,
                'siswa_id' => $siswa->id,
                'status' => 'hadir',
                'waktu_scan' => now(),
                'diabsenkan_oleh_user_id' => auth()->id(),
            ]);

            return response()->json([
                'status' => 'success',
                'nama'   => $siswa->nama_siswa,
                'kelas'  => $siswa->kelas->nama_kelas ?? '-',
                'mode'   => 'insert',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    }
}
