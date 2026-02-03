<?php

namespace App\Http\Controllers;

use App\Models\AbsensiGuru;
use App\Models\Jadwal;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbsensiGuruQr extends Controller
{
    public function index()
    {
        return view('scan-guru.scan-guru-qr');
    }

    public function scan(Request $request)
    {
        // =========================
        // VALIDASI REQUEST
        // =========================
        $request->validate([
            'qr'        => 'required|string',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // =========================
        // VALIDASI FORMAT QR
        // =========================
        if (! str_starts_with($request->qr, 'JADWAL:')) {
            return response()->json([
                'status'  => 'error',
                'message' => 'QR tidak valid',
            ]);
        }

        $jadwalId = (int) str_replace('JADWAL:', '', $request->qr);

        // =========================
        // AMBIL JADWAL + LEMBAGA
        // =========================
        $jadwal = Jadwal::with('lembaga')->find($jadwalId);

        if (! $jadwal || ! $jadwal->lembaga) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Jadwal atau lembaga tidak ditemukan',
            ]);
        }

        if (! $jadwal->jam_mulai) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Jam mulai jadwal belum diatur',
            ]);
        }

        $lembaga = $jadwal->lembaga;

        // =========================
        // VALIDASI KOORDINAT LEMBAGA
        // =========================
        if (! $lembaga->latitude || ! $lembaga->longitude) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Koordinat lembaga belum diatur',
            ]);
        }

        // =========================
        // VALIDASI KOORDINAT USER
        // =========================
        if ($request->latitude == 0 || $request->longitude == 0) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Lokasi Anda tidak terdeteksi',
            ]);
        }

        // =========================
        // AMBIL GURU LOGIN
        // =========================
        $guru = auth()->user()->guru ?? null;

        if (! $guru) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Akun tidak terhubung dengan data guru',
            ]);
        }

        // =========================
        // WAKTU WIB
        // =========================
        $waktuScan = now()->timezone('Asia/Jakarta');

        // =========================
        // CEK DOBEL ABSENSI
        // =========================
        $cek = AbsensiGuru::where([
            'lembaga_id' => $lembaga->id,
            'guru_id'    => $guru->id,
            'tanggal'    => $waktuScan->toDateString(),
        ])->first();

        if ($cek) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda sudah absen hari ini',
            ]);
        }

        // =========================
        // HITUNG JARAK
        // =========================
        $jarak = $this->hitungJarak(
            $request->latitude,
            $request->longitude,
            $lembaga->latitude,
            $lembaga->longitude
        );

        if ($jarak > $lembaga->radius_meter) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda di luar radius absensi',
            ]);
        }

        // =========================
        // STATUS MASUK (ENUM)
        // =========================
        $jamMulai = Carbon::today('Asia/Jakarta')
            ->setTimeFromTimeString($jadwal->jam_mulai);

        $batasTerlambat = $jamMulai->copy()->addMinutes($jadwal->batas_pas);

        $statusMasuk = 'belum_waktu';
        $keterangan  = 'Scan sebelum jam mulai (' . $waktuScan->format('H:i') . ')';

        if ($waktuScan->gte($jamMulai) && $waktuScan->lte($batasTerlambat)) {
            $statusMasuk = 'tepat_waktu';
            $keterangan  = 'Scan tepat waktu (' . $waktuScan->format('H:i') . ')';
        } elseif ($waktuScan->gt($batasTerlambat)) {
            $statusMasuk = 'terlambat';

            // ⏱ hitung selisih keterlambatan
            $selisihMenit = $batasTerlambat->diffInMinutes($waktuScan);

            if ($selisihMenit >= 60) {
                $jam   = intdiv($selisihMenit, 60);
                $menit = $selisihMenit % 60;

                $keterangan = "Terlambat {$jam} jam {$menit} menit (scan {$waktuScan->format('H:i')})";
            } else {
                $keterangan = "Terlambat {$selisihMenit} menit (scan {$waktuScan->format('H:i')})";
            }
        }

        // =========================
        // SIMPAN ABSENSI
        // =========================
        AbsensiGuru::create([
            'lembaga_id'   => $lembaga->id,
            'guru_id'      => $guru->id,
            'jadwal_id'    => $jadwal->id,
            'tanggal'      => $waktuScan->toDateString(),
            'waktu_scan'   => $waktuScan,
            'latitude'     => $request->latitude,
            'longitude'    => $request->longitude,
            'jarak_meter'  => $jarak,
            'radius_valid' => true,
            'metode'       => 'qr',
            'status'       => 'hadir',
            'status_masuk' => $statusMasuk,
            'keterangan'   => $keterangan,
        ]);
        WhatsappFilamentController::kirimRekapAbsensiGuruGroup(
            $jadwal->id
        );
        return response()->json([
            'status'       => 'success',
            'nama'         => $guru->nama,
            'status_masuk' => $statusMasuk,
            'keterangan'   => $keterangan,
            'jarak'        => $jarak,
        ]);
        // end kirim pesan ke group absensi guru
    }

    // =========================
    // RUMUS HAVERSINE
    // =========================
    private function hitungJarak($lat1, $lon1, $lat2, $lon2)
    {
        $earth = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1))
            * cos(deg2rad($lat2))
            * sin($dLon / 2) ** 2;

        return round($earth * (2 * atan2(sqrt($a), sqrt(1 - $a))));
    }
}
