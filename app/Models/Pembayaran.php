<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $fillable = [
        'id_pesanan','metode_pembayaran','tanggal'
    ];

    public function pesanan()
    {
        return $this->belongsTo(pesanan::class, 'id_pesanan', 'id');
    }
}
