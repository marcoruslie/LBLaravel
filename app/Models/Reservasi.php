<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    use HasFactory;

    protected $table = 'reservasi';
    public $timestamps = false;
    protected $fillable = [
        'tanggal_reservasi',
        'jam_reservasi',
        'keterangan',
        'status_reservasi',
        'jenis_aksesoris',
        'layanan_id',
        'pelanggan_id',
        'kendaraan_id',
    ];
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'reservasi_id');
    }

    public function aksesorisLayanan()
    {
        return $this->hasMany(AksesorisLayanan::class, 'reservasi_id');
    }
}
