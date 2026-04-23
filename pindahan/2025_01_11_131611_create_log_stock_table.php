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
        Schema::create('log_stock', function (Blueprint $table) {
            $table->id();
            $table->char('log_id', 6); #po_number atau id_transaksi
            $table->char('product_id', 50);
            $table->integer('old_stock')->default(0);
            $table->integer('new_stock')->default(0);
            $table->timestamps();
        });

        DB::unprepared('
            CREATE TRIGGER log_stock_increase
            AFTER INSERT ON purchase_order_detail
            FOR EACH ROW
            BEGIN
                UPDATE product SET current_stock = current_stock + NEW.quantity WHERE product_id = NEW.product_id;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_stock');
    }
};
