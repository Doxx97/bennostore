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
    Schema::table('products', function (Blueprint $table) {
        // Menambahkan kolom 'sold' dengan default 0
        $table->integer('sold')->default(0)->after('stock'); 
    });
    }

    public function down()
    {
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn('sold');
    });
    }
};
