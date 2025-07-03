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
        Schema::create('bill_of_material_item', function (Blueprint $table) {
            $table->id();
            $table->string('bom_id');       // Kode BoM
            $table->string('item_code');    // Kode item
            $table->integer('quantity');    // Jumlah item
            $table->timestamps();

            // Foreign key opsional:
            // $table->foreign('bom_id')->references('bom_id')->on('bill_of_material')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_of_material_item');
    }
};
