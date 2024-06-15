<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;
    protected $table = 'pelanggan';
    public $timestamps = false;
    public function reservasi()
    {
        return $this->hasMany(Reservasi::class, 'pelaanggan_id');
    }
    public function kendaraan()
    {
        return $this->hasMany(Kendaraan::class, 'pelanggan_id');
    }
}
