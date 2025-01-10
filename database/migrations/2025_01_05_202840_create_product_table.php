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
        Schema::create('product', function (Blueprint $table) {
            $table->char('product_id', 6)->primary;
            $table->string('name', 50);
            $table->tinyInteger('category_id');
            $table->string('description', 150);
            $table->string('measurement_unit', 10);
            $table->integer('current_stock')->default(0);
            $table->integer('avg_base_price')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
