<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $fillable = [
        'siswa_id',
        'jadwal_id',
        'diabsenkan_oleh_user_id',
        'status',
        'waktu_scan',
    ];

    protected $casts = [
        'waktu_scan' => 'datetime',
    ];

    /* ================= RELATION ================= */

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'diabsenkan_oleh_user_id');
    }
}
