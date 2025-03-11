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
        // Schema::dropIfExists('log_avg_base_price');

        Schema::create('log_avg_base_price', function (Blueprint $table) {
            $table->id();
            $table->char('product_id', 50);
            $table->integer('then_avg_base_price');
            $table->integer('now_avg_base_price');
            $table->timestamps();
        });

        DB::unprepared('
            CREATE TRIGGER log_avg_base_price
            AFTER UPDATE ON product
            FOR EACH ROW
            BEGIN
                IF OLD.avg_base_price != NEW.avg_base_price THEN
                    INSERT INTO log_avg_base_price (product_id, then_avg_base_price, now_avg_base_price, created_at, updated_at)
                    VALUES (NEW.product_id, OLD.avg_base_price, NEW.avg_base_price, NEW.updated_at, NEW.updated_at);
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_avg_base_price');
    }
};
