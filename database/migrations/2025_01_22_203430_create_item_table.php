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
        $column = config('db_constants.column.item');

        Schema::create(config('db_constants.table.item'), function (Blueprint $table) use ($column) {
            $table->id();
            $table->string($column['sku'], 50);
            $table->string($column['name'], 50);
            $table->string($column['measurement'], 6);
            $table->integer($column['base_price'])->default(0);
            $table->integer($column['selling_price'])->default(0);
            $table->timestamps();
            $table->primary([$column['id'], $column['sku']]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item');
    }
};
