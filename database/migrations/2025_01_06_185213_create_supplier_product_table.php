<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('supplier_product'); 
        #TODO tambah kolom nama suplier dan produk sekalian saja agar lebih cepat mendapatkan namanya
        Schema::create('supplier_product', function (Blueprint $table) {
            $table->char('supplier_id', 6);
            $table->char('product_id', 6);
            $table->primary(['supplier_id', 'product_id']);
            $table->integer('base_price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_supplier_product');
    }
};
