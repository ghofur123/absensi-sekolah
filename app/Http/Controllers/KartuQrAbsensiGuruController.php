<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jadwal;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class KartuQrAbsensiGuruController extends Controller
{
    public function pdf(Jadwal $jadwal)
    {
        // load relasi
        $jadwal->load('lembaga');

        // isi QR → ID jadwal
        $qrCode = new QrCode(
            data: 'JADWAL:' . $jadwal->id,
            size: 180,
            margin: 0
        );

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        $qrBase64 = base64_encode($result->getString());

        return Pdf::loadView('pdf.kartu-qr-absensi-guru', [
            'jadwal' => $jadwal,
            'qr' => $qrBase64,
        ])
            ->setPaper('A4', 'portrait')
            ->stream('qr-absensi-guru-jadwal-' . $jadwal->id . '.pdf');
    }
}
