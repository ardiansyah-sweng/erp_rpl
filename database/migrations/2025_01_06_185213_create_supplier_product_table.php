<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function __construct()
    {
        $this->table = config('db_constants.table.supplier_product');
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $col = config('db_constants.column.supplier_product');

        Schema::dropIfExists('supplier_product');

        Schema::create($this->table, function (Blueprint $table) use ($col) {
            $table->id();
            $table->char($col['supplier_id'], 6);
            $table->string($col['company_name'], 100);
            $table->char($col['product_id'], 50);
            $table->string($col['product_name'], 50);
            $table->integer($col['base_price']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->table);
    }
};
