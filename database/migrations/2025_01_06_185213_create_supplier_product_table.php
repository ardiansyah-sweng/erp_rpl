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

        Schema::create('supplier_product', function (Blueprint $table) {
            $table->char('supplier_id', 6);
            $table->string('company_name', 50);
            $table->char('product_id', 6);
            $table->string('product_name', 50);
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
        Schema::dropIfExists('supplier_product');
    }
};
