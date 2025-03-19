<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function __construct()
    {
        $this->table = config('db_constants.table.grn');
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $col = config('db_constants.column.grn');

        Schema::create($this->table, function (Blueprint $table) use ($col) {
            $table->id();
            $table->char($col['po_number'], 6);
            $table->string($col['product_id'], 50)->nullable();
            $table->date($col['date']); // Tanggal terima barang
            $table->integer($col['qty']); // Jumlah barang yang diterima
            $table->string($col['comments'], 255)->nullable();
            $table->timestamps();
        });

        /**
         * Buat trigger:
         * 1. Ambil acak avg_base_price, created_at
         * 2. Pikirkan bagaimana rentetan pertambahan stok akibat PO
         * 3. Padahal di antara PO tentu saja ada transaksi yang mengakibatkan berkurangnya stok
         */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->table);
    }
};
