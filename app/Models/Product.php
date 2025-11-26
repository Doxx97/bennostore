<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Jika ada
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // use HasFactory; // (Opsional, biarkan jika sudah ada)

    // === TAMBAHKAN KODE INI ===
    protected $fillable = [
        'name',
        'category',
        'price',
        'stock',
        'image',
        'sold',
    ];
}