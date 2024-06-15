<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;
    protected $table = 'kendaraan';
    public $timestamps = false;
    protected $fillable = [
        'nama',
        'tahun',
        'plat_nomor',
        'merek',
        'pelanggan_id',
    ];
    public function reservasi()
    {
        return $this->hasMany(Reservasi::class, 'kendaraan_id');
    }
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }
}
