<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'product_id', 'quantity'];

    // Relasi ke Produk agar bisa ambil nama, harga, gambar
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}