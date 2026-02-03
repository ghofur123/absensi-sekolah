<?php

namespace App\Support;

use App\Models\Absensi;
use Carbon\Carbon;

class AbsensiStatusHelper
{
    /**
     * Mapping semua format hari → angka standar (1=Senin ... 7=Minggu)
     * Ini tangkap semua kemungkinan format yang ada di database
     */
    protected static array $hariMapping = [
        // Full name Indonesia (lowercase)
        'senin'    => 1,
        'selasa'   => 2,
        'rabu'     => 3,
        'kamis'    => 4,
        'jumat'    => 5,
        'sabtu'    => 6,
        'minggu'   => 7,
        'ahad'     => 7,

        // Singkatan 3 huruf Indonesia (lowercase)
        'sen'      => 1,
        'sel'      => 2,
        'rab'      => 3,
        'kam'      => 4,
        'jum'      => 5,
        'sab'      => 6,
        'min'      => 7,

        // Singkatan 2 huruf
        'sn'       => 1,
        'sl'       => 2,
        'rb'       => 3,
        'km'       => 4,
        'jm'       => 5,
        'sb'       => 6,
        'mg'       => 7,

        // Full name English (lowercase)
        'monday'    => 1,
        'tuesday'   => 2,
        'wednesday' => 3,
        'thursday'  => 4,
        'friday'    => 5,
        'saturday'  => 6,
        'sunday'    => 7,

        // Singkatan English
        'mon' => 1,
        'tue' => 2,
        'wed' => 3,
        'thu' => 4,
        'fri' => 5,
        'sat' => 6,
        'sun' => 7,
    ];

    /**
     * Konversi string hari (apapun formatnya) → angka 1-7
     * Return null kalau tidak dikenali
     */
    protected static function hariKeAngka(string $hari): ?int
    {
        $normalized = strtolower(trim($hari));
        return self::$hariMapping[$normalized] ?? null;
    }

    /**
     * Hitung keterangan hadir
     */
    public static function statusMasuk(Absensi $absensi): ?string
    {
        if ($absensi->status !== 'hadir') {
            return null;
        }

        if (!$absensi->waktu_scan || !$absensi->jadwal) {
            return null;
        }

        $scan = Carbon::parse($absensi->waktu_scan);

        // Hari scan → angka (Carbon: 1=Senin, 7=Minggu)
        $angkaScan = $scan->isoWeekday();

        // Hari jadwal dari database → konversi ke angka
        $angkaJadwal = [];
        foreach ($absensi->jadwal->hari as $h) {
            $angka = self::hariKeAngka($h);
            if ($angka !== null) {
                $angkaJadwal[] = $angka;
            }
        }

        // Kalau hari scan tidak ada di jadwal → Di Luar Jadwal
        if (!in_array($angkaScan, $angkaJadwal)) {
            return 'Di Luar Jadwal';
        }

        // Hitung toleransi waktu
        $mulai   = $scan->copy()->setTimeFromTimeString($absensi->jadwal->jam_mulai);
        $selesai = $scan->copy()->setTimeFromTimeString($absensi->jadwal->jam_selesai);

        // Lintas malam
        if ($selesai->lte($mulai)) {
            $selesai->addDay();
        }

        $batasAwal = $mulai->copy()->subMinutes(($absensi->jadwal->batas_awal));
        $batasPas  = $mulai->copy()->addMinutes(($absensi->jadwal->batas_pas));

        if ($scan->lt($batasAwal)) {
            return 'Terlalu Awal';
        }

        if ($scan->lte($batasPas)) {
            return 'Tepat Waktu';
        }

        if ($scan->lte($selesai)) {
            return 'Terlambat';
        }

        return 'Di Luar Jadwal';
    }

    /**
     * Kode singkat laporan
     */
    public static function kode(?string $statusMasuk, ?string $statusDb): string
    {
        if ($statusDb === 'izin')  return 'I';
        if ($statusDb === 'sakit') return 'S';
        if ($statusDb === 'alpa')  return 'A';

        // hadir: hanya "Di Luar Jadwal" yang tidak dihitung H
        if ($statusMasuk === 'Di Luar Jadwal') {
            return '-';
        }

        // Tepat Waktu, Terlambat, Terlalu Awal → H
        return 'H';
    }
}
