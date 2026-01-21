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
        // ambil relasi
        $kelas->load('lembaga', 'siswas');

        $siswas = $kelas->siswas->where('status', 'aktif');

        // generate QR per siswa
        $qrs = [];

        foreach ($siswas as $siswa) {
            $qrCode = new QrCode(
                data: $siswa->id,
                size: 120,
                margin: 0
            );

            $writer = new PngWriter();
            $result = $writer->write($qrCode);

            $qrs[$siswa->id] = base64_encode($result->getString());
        }

        return Pdf::loadView('pdf.kartu-siswa', [
            'kelas' => $kelas,
            'siswas' => $siswas,
            'qrs' => $qrs,
        ])
            ->setPaper('A4', 'portrait')
            ->stream('kartu-siswa-' . $kelas->nama_kelas . '.pdf');
    }
}
