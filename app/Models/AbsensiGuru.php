<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiGuru extends Model
{
    protected $fillable = [
        'lembaga_id',
        'guru_id',
        'jadwal_id',
        'tanggal',
        'waktu_scan',
        'latitude',
        'longitude',
        'jarak_meter',
        'radius_valid',
        'metode',
        'status',
        'status_masuk',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_scan' => 'datetime',
        'radius_valid' => 'boolean',
    ];

    public function lembaga()
    {
        return $this->belongsTo(Lembaga::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }
}
