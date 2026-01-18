<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    protected $fillable = [
        'lembaga_id',
        'nama_mapel',
    ];

    public function lembaga()
    {
        return $this->belongsTo(Lembaga::class);
    }
}
