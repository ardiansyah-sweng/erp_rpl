<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableName = config('db_constants.table.purchase_return', 'purchase_returns');
        $col = config('db_constants.column.purchase_return');

        Schema::create($tableName, function (Blueprint $table) use ($col) {
            $table->id();
            $table->string($col['return_number'], 24)->unique();
            $table->char($col['po_number'], 6)->index();
            $table->string($col['product_id'], 50)->index();
            $table->date($col['return_date']);
            $table->unsignedInteger($col['quantity']);
            $table->string($col['reason'], 30);
            $table->string($col['notes'], 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('db_constants.table.purchase_return', 'purchase_returns'));
    }
};
