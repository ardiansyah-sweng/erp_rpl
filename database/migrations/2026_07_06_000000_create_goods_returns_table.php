<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function __construct()
    {
        $this->table = config('db_constants.table.goods_return', 'goods_returns');
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $col = config('db_constants.column.goods_return');

        Schema::create($this->table, function (Blueprint $table) use ($col) {
            $table->id();
            $table->unsignedBigInteger($col['grn_id']);
            $table->char($col['po_number'], 6);
            $table->string($col['product_id'], 50);
            $table->date($col['date']);
            $table->unsignedInteger($col['qty']);
            $table->string($col['reason'], 255);
            $table->string('bukti_lampiran', 255)->nullable();
            $table->timestamps();

            $table->index([$col['po_number'], $col['product_id']]);
            $table->foreign($col['grn_id'])
                ->references('id')
                ->on(config('db_constants.table.grn'))
                ->restrictOnDelete();
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
