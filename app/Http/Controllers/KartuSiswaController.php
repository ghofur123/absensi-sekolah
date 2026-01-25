<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;

class KartuSiswaController extends Controller
{
    public function pdf(Kelas $kelas)
    {
        // 1. Ambil relasi dengan eager loading
        $kelas->load('lembaga', 'siswas');

        // 2. Filter siswa aktif
        $siswas = $kelas->siswas->where('status', 'aktif');

        // 3. Generate QR per siswa
        $qrs = [];
        $writer = new PngWriter();

        foreach ($siswas as $siswa) {
            $qrCode = new QrCode(
                data: $siswa->id,
                size: 150, // Sedikit lebih besar agar hasil print tajam
                margin: 0
            );

            $result = $writer->write($qrCode);
            $qrs[$siswa->id] = base64_encode($result->getString());
        }

        // 4. Inisialisasi PDF
        $pdf = Pdf::loadView('pdf.kartu-siswa', [
            'kelas' => $kelas,
            'siswas' => $siswas,
            'qrs' => $qrs,
        ]);

        // 5. Konfigurasi penting: Aktifkan Remote Enabled untuk gambar/logo dari URL
        $pdf->getDomPDF()->set_option("isRemoteEnabled", true);
        $pdf->getDomPDF()->set_option("isHtml5ParserEnabled", true);

        // 6. Set ukuran kertas dan stream
        return $pdf->setPaper('A4', 'portrait')
            ->stream('kartu-siswa-' . $kelas->nama_kelas . '.pdf');
    }
}
