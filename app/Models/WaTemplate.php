<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaTemplate extends Model
{
    protected $fillable = [
        'lembaga_id',

        // orang tua
        'header_orang_tua',
        'footer_orang_tua',
        'aktif_orang_tua',

        // guru
        'header_guru',
        'footer_guru',
        'aktif_guru',
    ];

    protected $casts = [
        'aktif_orang_tua' => 'boolean',
        'aktif_guru'      => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function lembaga()
    {
        return $this->belongsTo(Lembaga::class);
    }
}
