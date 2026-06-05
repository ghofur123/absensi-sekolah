<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $fillable = [
        'lembaga_id',
        'user_id',
        'nama',
        'nik',
        'status_pegawai',
        'status_sertifikasi',
    ];

    public function lembaga()
    {
        return $this->belongsTo(Lembaga::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
