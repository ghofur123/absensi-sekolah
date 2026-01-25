<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaTemplate extends Model
{
    protected $fillable = [
        'lembaga_id',
        'header',
        'footer',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function lembaga()
    {
        return $this->belongsTo(Lembaga::class);
    }
}
