<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aksesoris extends Model
{
    use HasFactory;
    protected $table = 'aksesoris';
    public $timestamps = false;
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
    public function aksesorisLayanan()
    {
        return $this->hasMany(AksesorisLayanan::class, 'aksesoris_id');
    }
}
