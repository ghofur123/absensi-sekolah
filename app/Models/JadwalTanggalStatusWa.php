<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalTanggalStatusWa extends Model
{
    protected $fillable = [
        'jadwal_id',
        'tanggal',
        'sudah_dikirim',
        'waktu_kirim',
        'dikirim_oleh_user_id',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'sudah_dikirim' => 'boolean',
        'waktu_kirim' => 'datetime',
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'dikirim_oleh_user_id');
    }
}
