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
        $tableItem = config('db_constants.table.item');

        Schema::create($tableItem, function (Blueprint $table) use ($column) {
            $table->id();
            $table->char($column['prod_id'], 4);
            $table->string($column['sku'], 50);
            $table->string($column['name'], 50);
            $table->string($column['measurement'], 6);
            $table->integer($column['base_price'])->default(0);
            $table->integer($column['selling_price'])->default(0);
            $table->tinyInteger($column['purchase_unit'])->default(30); #30 kode unit Pieces di tabel measurement_unit
            $table->tinyInteger($column['sell_unit'])->default(30);
            $table->tinyInteger($column['stock_unit'])->default(30);
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
