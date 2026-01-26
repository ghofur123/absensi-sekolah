<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WhatsappController extends Controller
{
    public function kirim()
    {
        // Nomor tujuan (format Indonesia, tanpa 0 di depan)
        $noWa = '082141031276';

        // Contoh data
        $item = [
            'nama_siswa' => 'Abdul Gafur',
            'status'     => 'hadir',
        ];

        $pesan =
            "Assalamu’alaikum Wr. Wb.\n\n"
            . "Kami informasikan bahwa:\n"
            . "Nama : {$item['nama_siswa']}\n"
            . "Status kehadiran hari ini: *" . strtoupper($item['status']) . "*\n\n"
            . "Terima kasih.\n";

        $token = "r3UpUKoSLk17fkytNyMB";

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

        if ($error) {
            return response()->json([
                'status' => false,
                'error'  => $error,
            ], 500);
        }

        return response()->json([
            'status'   => true,
            'response' => json_decode($response, true),
        ]);
    }
}
