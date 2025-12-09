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
            // Cek dulu biar gak error kalau dijalankan ulang
            if (!Schema::hasColumn('orders', 'payment_proof')) {
                $table->string('payment_proof')->nullable()->after('total_price');
            }
        });
    }

    public function down()
    {
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn('payment_proof');
    });
    }
};
