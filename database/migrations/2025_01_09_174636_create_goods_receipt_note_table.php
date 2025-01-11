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
        Schema::create('goods_receipt_note', function (Blueprint $table) {
            $table->id();
            $table->char('po_number', 6);
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
        Schema::dropIfExists('goods_receipt_note');
    }
};
