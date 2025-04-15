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
    Schema::create('supplier_materials', function (Blueprint $table) {
        $table->id();
        $table->string('material_name');
        $table->unsignedBigInteger('supplier_id'); // foreign key
        $table->timestamps();

        $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_materials');
    }
};
