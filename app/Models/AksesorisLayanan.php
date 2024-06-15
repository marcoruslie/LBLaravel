<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AksesorisLayanan extends Model
{
    use HasFactory;
    protected $table = 'aksesoris_layanan';
    public $timestamps = false;
    protected $fillable = [
        'aksesoris_id',
        'reservasi_id',
        'jumlah',
    ];
    public function aksesoris()
    {
        return $this->belongsTo(Aksesoris::class, 'aksesoris_id');
    }
    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'reservasi_id');
    }
}
