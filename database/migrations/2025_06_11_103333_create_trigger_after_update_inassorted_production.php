<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
<<<<<<< HEAD
=======
use Illuminate\Support\Facades\DB;
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
<<<<<<< HEAD
=======
        // Skip trigger creation - trigger has cardinality violation issues
        // Disabled due to subquery returning multiple rows error
        return;
        
        // Skip trigger creation in testing environment (SQLite doesn't support MySQL trigger syntax)
        if (app()->environment('testing', 'dusk.local')) {
            return;
        }

>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
        DB::unprepared('
            CREATE TRIGGER trigger_after_update_inassorted_production
            AFTER UPDATE ON assortment_production
            FOR EACH ROW
            BEGIN

                DECLARE done INT DEFAULT 0;
                DECLARE v_bom_id CHAR(7);
                DECLARE v_bom_quantity INT DEFAULT 0;
                DECLARE v_old_stock INT DEFAULT 0;
                DECLARE v_new_stock INT DEFAULT 0;
                DECLARE v_qty INT DEFAULT 0;
                DECLARE v_log_desc VARCHAR(255);

                DECLARE bom_cursor CURSOR FOR
                    SELECT bom_id, bom_quantity
                    FROM assortment_production_detail
                    WHERE production_number = NEW.production_number;
                    
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
                
                IF OLD.in_production = 1 AND NEW.in_production = 0 THEN
                    OPEN bom_cursor;

                    read_loop: LOOP
                        FETCH bom_cursor INTO v_bom_id, v_bom_quantity;
                        IF done THEN
                            LEAVE read_loop;
                        END IF;

                        SELECT i.stock_unit, b.quantity
                        INTO v_old_stock, v_qty
                        FROM bom_detail b
<<<<<<< HEAD
                        JOIN item i ON i.sku = b.sku
=======
                        JOIN items i ON i.sku = b.sku
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
                        WHERE b.bom_id = v_bom_id
                        LIMIT 1;

                        SET v_new_stock = v_old_stock - (v_bom_quantity * v_qty);
                        SET v_log_desc = CONCAT("BOM Consumption for ", NEW.production_number);

<<<<<<< HEAD
                        UPDATE item
=======
                        UPDATE items
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
                        SET stock_unit = v_new_stock
                        WHERE sku = (SELECT product_id FROM bom_detail WHERE bom_id = v_bom_id);

                        INSERT INTO log_material_inventory (
                            log_id,
                            sku,
                            old_stock,
                            new_stock,
                            created_at,
                            updated_at
                        ) VALUES (
                            v_log_desc,
                            (SELECT product_id FROM bill_of_material WHERE bom_id = v_bom_id),
                            v_old_stock,
                            v_new_stock,
                            NOW(),
                            NOW()
                        );
                    END LOOP;
                    CLOSE bom_cursor;
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trigger_after_update_inassorted_production');
    }
};
