<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $fillable = [
        'lembaga_id',
        'guru_id',
        'mata_pelajaran_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'batas_awal',
        'batas_pas',
    ];
    protected $casts = [
        'hari' => 'array',
    ];
    public function lembaga()
    {
        return $this->belongsTo(Lembaga::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }
    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }
    // pivot
    public function kelases()
    {
        return $this->belongsToMany(Kelas::class, 'jadwal_kelas');
    }

    public function jadwalKelas()
    {
        return $this->hasMany(JadwalKelas::class);
    }
    public function jadwalTanggalStatusWa()
    {
        return $this->hasOne(JadwalTanggalStatusWa::class);
    }
}
