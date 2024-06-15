<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;
    protected $table = 'layanan';
    public $timestamps = false;
    public function reservasi()
    {
        return $this->hasMany(Reservasi::class, 'layanan_id');
    }
}
