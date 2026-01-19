<?php

namespace App\Imports;

use App\Models\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class SiswaImport implements ToCollection
{
    protected $lembagaId;
    protected $kelasId;

    public function __construct($lembagaId, $kelasId)
    {
        $this->lembagaId = $lembagaId;
        $this->kelasId   = $kelasId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {

            // Skip header kalau baris pertama teks
            if ($index === 0 && !is_numeric($row[0])) {
                continue;
            }

            // Validasi minimal
            if (!isset($row[0]) || !isset($row[1])) {
                continue;
            }

            // NORMALISASI JENIS KELAMIN
            $jk = null;
            if (isset($row[2])) {
                $val = strtolower(trim($row[2]));

                if (in_array($val, ['l', 'laki', 'laki-laki'])) {
                    $jk = 'Laki-laki';
                } elseif (in_array($val, ['p', 'perempuan'])) {
                    $jk = 'Perempuan';
                }
            }

            Siswa::create([
                'lembaga_id'    => $this->lembagaId,
                'kelas_id'      => $this->kelasId,
                'nisn'          => trim($row[0]),
                'nama_siswa'    => trim($row[1]),
                'jenis_kelamin' => $jk ?? 'Laki-laki', // default aman
                'alamat'        => $row[3] ?? null,
                'status'        => $row[4] ?? 'aktif',
                'no_wa'         => $row[5] ?? null,
            ]);
        }
    }
}
