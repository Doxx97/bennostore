<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()    
    {
    Schema::table('orders', function (Blueprint $table) {
        // Default 'pickup' (Ambil di Toko) atau 'delivery' (Diantar)
        $table->string('delivery_method')->default('pickup')->after('payment_method');
    });
    }

    public function down()
    {
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn('delivery_method');
    });
    }
};
