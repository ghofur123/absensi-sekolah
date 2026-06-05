<?php

namespace App\Http\Controllers;

use App\Models\AbsensiGuru;
use App\Models\Jadwal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
            ], 422);
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
            ], 404);
        }

        if (! $jadwal->jam_mulai) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Jam mulai jadwal belum diatur',
            ], 422);
        }

        $lembaga = $jadwal->lembaga;

        // =========================
        // VALIDASI KOORDINAT LEMBAGA
        // =========================
        if (! $lembaga->latitude || ! $lembaga->longitude) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Koordinat lembaga belum diatur',
            ], 422);
        }

        // =========================
        // VALIDASI KOORDINAT USER
        // =========================
        if ($request->latitude == 0 || $request->longitude == 0) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Lokasi Anda tidak terdeteksi',
            ], 422);
        }

        // =========================
        // AMBIL GURU LOGIN
        // =========================
        $guru = auth()->user()?->guru;

        if (! $guru) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Akun tidak terhubung dengan data guru',
            ], 403);
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
            ], 409);
        }

        // =========================
        // HITUNG JARAK (HAVERSINE)
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
            ], 403);
        }

        // =========================
        // HITUNG STATUS MASUK (SESUI ENUM DB)
        // ENUM DB: tepat_waktu, terlambat
        // =========================
        $jamMulai = Carbon::today('Asia/Jakarta')
            ->setTimeFromTimeString($jadwal->jam_mulai);

        $batasTerlambat = $jamMulai->copy()->addMinutes($jadwal->batas_pas);

        $statusMasuk = null;
        $keterangan  = null;

        if ($waktuScan->lt($jamMulai)) {
            $statusMasuk = 'belum_waktu';
            $keterangan = 'Scan sebelum jam mulai (' . $waktuScan->format('H:i') . ')';
        } elseif ($waktuScan->lte($batasTerlambat)) {
            $statusMasuk = 'tepat_waktu';
            $keterangan = 'Scan tepat waktu (' . $waktuScan->format('H:i') . ')';
        } else {
            $statusMasuk = 'terlambat';
            $selisih = $batasTerlambat->diffInMinutes($waktuScan);

            if ($selisih >= 60) {
                $jam = intdiv($selisih, 60);
                $menit = $selisih % 60;
                $keterangan = "Terlambat {$jam} jam {$menit} menit (scan {$waktuScan->format('H:i')})";
            } else {
                $keterangan = "Terlambat {$selisih} menit (scan {$waktuScan->format('H:i')})";
            }
        }

        // =========================
        // SIMPAN ABSENSI (SAFE)
        // =========================
        try {
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
        } catch (\Throwable $e) {
            Log::error('Gagal simpan absensi guru', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan absensi',
            ], 500);
        }

        // =========================
        // KIRIM WHATSAPP (OPTIONAL)
        // =========================
        // try {
        WhatsappFilamentController::kirimRekapAbsensiGuruGroup(
            $jadwal->id
        );
        // } catch (\Throwable $e) {
        //     \Log::error('Gagal kirim WA absensi guru', [
        //         'jadwal_id' => $jadwal->id,
        //         'error' => $e->getMessage(),
        //     ]);
        // }
        // =========================
        // RESPONSE SUKSES
        // =========================
        return response()->json([
            'status'       => 'success',
            'nama'         => $guru->nama,
            'status_masuk' => $statusMasuk,
            'keterangan'   => $keterangan,
            'jarak'        => $jarak,
        ]);
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
