<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Lembaga extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $fillable = [
        'nama_lembaga',
        'alamat',
        'latitude',
        'longitude',
        'radius_meter',
    ];
    public function gurus()
    {
        return $this->hasMany(Guru::class);
    }
    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }
    public function lembagaSetting()
    {
        return $this->hasOne(LembagaSetting::class);
    }
}
