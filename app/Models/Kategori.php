<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tiket;
use App\Models\Pesanan;

class Kategori extends Model
{
    protected $fillable = [
        'nama_kategori','harga','jumlah','id_tiket'
    ];

    public function tiket()
    {
        return $this->belongsTo(tiket::class, 'id_tiket', 'id_tiket');
    }

    public function kategori()
    {
        return $this->hasMany(pesanan::class, 'id_kategori', 'id');
    }
}
