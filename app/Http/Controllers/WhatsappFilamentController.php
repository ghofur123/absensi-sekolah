<?php

namespace App\Http\Controllers;

use App\Models\AbsensiGuru;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\LembagaSetting;
use App\Models\WaTemplate;
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
        int $lembagaId
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
        $template = WaTemplate::firstOrNew([
            'lembaga_id' => $lembagaId,
        ]);

        $pesan =
            $template->header_orang_tua . "\n"
            . "Nama : {$namaSiswa}\n"
            . "Status kehadiran hari ini: *" . strtoupper($status) . "*\n\n"
            . $template->footer_orang_tua . "\n";

        // ambil token
        $token = LembagaSetting::where('lembaga_id', $lembagaId)->first()->fonnte_token;
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
                'Authorization: ' . $token,
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
        try {
            $tanggal = now()->toDateString();

            $jadwal = Jadwal::with(['lembaga.lembagaSetting'])
                ->findOrFail($jadwalId);

            $token = optional($jadwal->lembaga->lembagaSetting)->fonnte_token;
            $groupId = '120363403312912675@g.us';

            if (! $token) {
                Log::error('Setting WA guru belum lengkap', [
                    'lembaga_id' => $jadwal->lembaga_id
                ]);
                return false;
            }

            // ===============================
            // AMBIL ABSENSI HARI INI (PASTI VALID)
            // ===============================
            $absensiHariIni = AbsensiGuru::where('jadwal_id', $jadwalId)
                ->whereDate('tanggal', $tanggal)
                ->get()
                ->keyBy('guru_id');

            // ===============================
            // AMBIL GURU DARI ABSENSI (FIX UTAMA)
            // ===============================
            $semuaGuru = Guru::where('lembaga_id', $jadwal->lembaga_id)->get();

            $sudahAbsen = [];
            $belumAbsen = [];

            foreach ($semuaGuru as $guru) {

                if ($absensiHariIni->has($guru->id)) {

                    $absen = $absensiHariIni[$guru->id];
                    $ket = $absen->keterangan ?? ucfirst($absen->status);

                    $sudahAbsen[] =
                        "*{$guru->nama}* ({$ket})" .
                        " Sertifikasi : {$guru->status_sertifikasi}";
                } else {

                    $belumAbsen[] =
                        "{$guru->nama}" .
                        " Sertifikasi : {$guru->status_sertifikasi}";
                }
            }


            // ===============================
            // TEMPLATE WA
            // ===============================
            $template = WaTemplate::firstOrNew([
                'lembaga_id' => $jadwal->lembaga_id,
            ]);

            $header = $template->header_guru ?? '📊 *REKAP ABSENSI GURU*';
            $footer = $template->footer_guru ?? '';

            $tanggalFormat = now()->translatedFormat('l, d F Y');

            $pesan =
                $header . "\n"
                . "🗓 {$tanggalFormat}\n\n"
                . "✅ *SUDAH ABSEN* (" . count($sudahAbsen) . ")\n"
                . self::buatList($sudahAbsen) . "\n\n"
                . "❌ *BELUM ABSEN* (" . count($belumAbsen) . ")\n"
                . self::buatList($belumAbsen) . "\n\n"
                . $footer;

            // ===============================
            // KIRIM KE FONNTE
            // ===============================
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => [
                    'target'  => $groupId,
                    'message' => $pesan,
                ],
                CURLOPT_HTTPHEADER => [
                    'Authorization: ' . $token,
                ],
            ]);

            $response = curl_exec($curl);
            $error = curl_error($curl);
            curl_close($curl);

            if ($error) {
                Log::error('Fonnte Group Error', compact('error', 'response'));
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('Gagal kirim WA rekap guru', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
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
