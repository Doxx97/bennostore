<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'orders';

    // Kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'user_id',
        'recipient_name',   // Nama penerima
        'address',          // Alamat pengiriman
        'payment_method',   // qris atau bni
        'total_price',      // Total bayar
        'status',           // pending, paid, shipped, dll
        'invoice_number',   // INV-XXXXX
        'delivery_method',  // pickup atau delivery
    ];

    /**
     * Relasi: Order milik satu User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Order memiliki banyak Item (Detail Produk)
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}