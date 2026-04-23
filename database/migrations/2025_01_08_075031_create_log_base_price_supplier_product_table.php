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
        Schema::create('log_base_price_supplier_product', function (Blueprint $table) {
            $table->id();
            $table->char('supplier_id', 6);
            $table->char('product_id', 50);
            $table->integer('old_base_price');
            $table->integer('new_base_price');
            $table->timestamps();
        });

        DB::unprepared('
            CREATE TRIGGER log_base_price_supplier_product
            AFTER UPDATE ON supplier_product
            FOR EACH ROW
            BEGIN
                IF OLD.base_price != NEW.base_price THEN
                    INSERT INTO log_base_price_supplier_product (supplier_id, product_id, old_base_price, new_base_price)
                    VALUES (NEW.supplier_id, NEW.product_id, OLD.base_price, NEW.base_price);
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_base_price_supplier_product');
    }
};
