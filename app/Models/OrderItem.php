<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    // Relasi ke tabel Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi ke tabel Product (supaya bisa ambil nama/gambar produk nanti)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}