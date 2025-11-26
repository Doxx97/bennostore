<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Terhubung ke user
            
            // Kolom Informasi Pengiriman
            $table->string('recipient_name')->nullable();
            $table->text('address')->nullable();
            
            // Kolom Pembayaran & Status
            $table->string('payment_method')->default('manual'); // qris / bni
            $table->decimal('total_price', 15, 2);
            $table->string('status')->default('pending'); // pending, paid, shipped
            $table->string('invoice_number');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};