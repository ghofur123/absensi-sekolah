<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $fillable = [
        'lembaga_id',
        'kelas_id',
        'nisn',
        'nama_siswa',
        'jenis_kelamin',
        'alamat',
        'status',
        'no_wa',
    ];

    public function lembaga()
    {
        return $this->belongsTo(Lembaga::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }
}
