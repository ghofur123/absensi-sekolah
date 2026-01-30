<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $fillable = [
        'lembaga_id',
        'nama_kelas',
        'tingkat',
    ];

    public function lembaga()
    {
        return $this->belongsTo(Lembaga::class);
    }
    public function siswas()
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }
    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'kelas_id');
    }
    // pivot
    public function jadwals()
    {
        return $this->belongsToMany(Jadwal::class, 'jadwal_kelas');
    }
}
