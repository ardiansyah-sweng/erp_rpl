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
        $column = config('db_constants.column.po_detail');
        $tablePODetail = config('db_constants.table.po_detail');

        Schema::create($tablePODetail, function (Blueprint $table) use ($column) {
            $table->char($column['po_number'], 6);
            $table->string($column['product_id'], 50);
            $table->integer($column['base_price'])->default(0);
            $table->integer($column['quantity']);
            $table->bigInteger($column['amount']); #quantity x base_price
            $table->tinyInteger($column['received_days'])->default(0);
            $table->timestamps();

            $table->primary(['po_number', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_detail');
    }
};
