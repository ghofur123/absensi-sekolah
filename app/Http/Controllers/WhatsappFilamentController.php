<?php

namespace App\Http\Controllers;

use App\Models\AbsensiGuru;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Scalar\String_;

class WhatsappFilamentController extends Controller
{
    /**
     * Kirim WhatsApp Absensi via Fonnte
     */
    public static function kirimAbsensi(
        string $noWa,
        string $namaSiswa,
        string $status,
        // string $token
    ) {

        // ======================
        // VALIDASI NOMOR
        // ======================
        $noWa = preg_replace('/[^0-9]/', '', $noWa);

        if (str_starts_with($noWa, '0')) {
            $noWa = substr($noWa, 1);
        }

        if (empty($noWa) || strlen($noWa) < 9) {
            Log::warning('Nomor WA tidak valid', [
                'no_wa' => $noWa,
            ]);
            return false;
        }

        // ======================
        // FORMAT PESAN
        // ======================
        $pesan =
            "Informasi Absensi Peserta Didik\n"
            . ". اَلسَّلَامُ عَلَيْكُمْ وَرَحْمَةُ اللهِ وَبَرَكَا تُهُ\n"
            . "YPI Nurul Mannan\n"
            . "================================\n"
            . "Nama : {$namaSiswa}\n"
            . "Status kehadiran hari ini: *" . strtoupper($status) . "*\n\n"
            . "================================\n"
            . "Tidak Perlu Balas Pesan Ini Karena Pesan Otomatis dari sistem kami\n"
            . "ada pertanyaan silahkan hubungi kami di nomor ini\n"
            . "Admin 1 : 08123456789\n"
            . "Admin 2 : 08123456789\n"
            . "YPI Nurul Mannan\n";

        // ======================
        // CURL KE FONNTE
        // ======================
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'target' => $noWa,
                'message' => $pesan,
                'countryCode' => '62',
            ],
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . "r3UpUKoSLk17fkytNyMB",
            ],
        ]);

        $response = curl_exec($curl);
        $error    = curl_error($curl);

        curl_close($curl);

        // ======================
        // LOGGING
        // ======================
        if ($error) {
            Log::error('Fonnte Error', [
                'no_wa' => $noWa,
                'error' => $error,
            ]);
            return false;
        }

        Log::info('Fonnte Success', [
            'no_wa' => $noWa,
            'response' => $response,
        ]);

        return true;
    }

    public static function kirimRekapAbsensiGuruGroup(int $jadwalId)
    {
        $tanggal = now()->toDateString();

        // 1. Ambil jadwal
        $jadwal = Jadwal::with('lembaga.gurus')->findOrFail($jadwalId);

        // 2. Semua guru di lembaga
        $semuaGuru = $jadwal->lembaga->gurus;

        // 3. Ambil absensi hari ini
        $absensiHariIni = AbsensiGuru::where('jadwal_id', $jadwalId)
            ->whereDate('tanggal', $tanggal)
            ->get()
            ->keyBy('guru_id');

        $sudahAbsen = [];
        $belumAbsen = [];

        foreach ($semuaGuru as $guru) {
            if ($absensiHariIni->has($guru->id)) {
                $sudahAbsen[] = $guru->nama;
            } else {
                $belumAbsen[] = $guru->nama;
            }
        }

        // 4. Format pesan
        $tanggalFormat = now()->translatedFormat('l, d F Y');

        $pesan =
            "📋 *ABSENSI GURU HARI INI*\n"
            . "🗓 {$tanggalFormat}\n\n"

            . "✅ *SUDAH ABSEN* (" . count($sudahAbsen) . ")\n"
            . self::buatList($sudahAbsen) . "\n\n"

            . "❌ *BELUM ABSEN* (" . count($belumAbsen) . ")\n"
            . self::buatList($belumAbsen) . "\n\n"

            . "—\n"
            . "_Pesan otomatis dari Sistem Absensi Digital_";

        // 5. Kirim WA Group
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'target'  => "120363403312912675@g.us",
                'message' => $pesan,
            ],
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . "r3UpUKoSLk17fkytNyMB",
            ],
        ]);

        curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        return empty($error);
    }
    private static function buatList(array $data): string
    {
        if (count($data) === 0) {
            return "-";
        }

        return collect($data)
            ->values()
            ->map(fn($nama, $i) => ($i + 1) . ". {$nama}")
            ->implode("\n");
    }
}
