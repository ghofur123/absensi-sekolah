<?php

namespace App\Imports;

use App\Models\Guru;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GuruImport implements ToCollection, WithHeadingRow
{
    protected int $lembagaId;
    public function __construct(int $lembagaId)
    {
        $this->lembagaId = $lembagaId;
    }
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (empty($row['nama'])) {
                continue;
            }
            Guru::create([
                'lembaga_id' => $this->lembagaId,
                'nama'       => $row['nama'],
                'nik'        => $row['nik'],
            ]);
        }
    }
}
