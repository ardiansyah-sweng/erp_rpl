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
        $column = config('db_constants.column.product');
        Schema::create(config('db_constants.table.product'), function (Blueprint $table) use ($column) {
            $table->char($column['product_id'], 6)->primary;
            $table->string($column['name'], 50);
            $table->tinyInteger($column['category_id']);
            $table->string($column['description'], 150);
            $table->string($column['measurement'], 10);
            $table->integer($column['stock'])->default(0);
            $table->integer($column['base_price'])->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(config('db_constants.table.product'));
    }
};
