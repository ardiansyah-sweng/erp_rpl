<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Skip trigger creation in testing environment (SQLite doesn't support MySQL trigger syntax)
        if (app()->environment('testing', 'dusk.local')) {
            return;
        }

        DB::unprepared('
            CREATE TRIGGER after_insert_grn
            AFTER INSERT ON goods_receipt_note
            FOR EACH ROW
            BEGIN
                DECLARE v_old_stock INT DEFAULT 0;
                DECLARE v_new_stock INT DEFAULT 0;
                DECLARE v_log_desc CHAR(50);

                SELECT stock_unit INTO v_old_stock
                FROM item
                WHERE sku = NEW.product_id
                LIMIT 1;

                SET v_new_stock = v_old_stock + NEW.delivered_quantity;
                SET v_log_desc = CONCAT("GRN from PO#", NEW.po_number);

                UPDATE item
                SET stock_unit = v_new_stock
                WHERE sku = NEW.product_id;

                INSERT INTO log_material_inventory (
                    log_id,
                    sku,
                    old_stock,
                    new_stock,
                    created_at,
                    updated_at
                ) VALUES (
                    v_log_desc,
                    NEW.product_id,
                    v_old_stock,
                    v_new_stock,
                    NOW(),
                    NOW()
                );
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trigger_after_insert_grn');
    }
};
