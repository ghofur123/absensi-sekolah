<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LembagaSetting extends Model
{
    protected $fillable = [
        'lembaga_id',
        'wa_absensi_enabled',
        'fonnte_token',
        'kirim_hadir',
        'kirim_izin',
        'kirim_sakit',
        'kirim_alpa',
    ];

    protected $casts = [
        'wa_absensi_enabled' => 'boolean',
        'kirim_hadir' => 'boolean',
        'kirim_izin' => 'boolean',
        'kirim_sakit' => 'boolean',
        'kirim_alpa' => 'boolean',
    ];

    public function lembaga()
    {
        return $this->belongsTo(Lembaga::class);
    }
    public function waTemplate()
    {
        return $this->hasOne(WaTemplate::class, 'lembaga_id', 'lembaga_id');
    }
}
