<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Constants\ItemColumns;

return new class extends Migration
{
    protected string $table;

    public function __construct()
    {
        $this->table = config('db_tables.item');
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->id();
            $table->char(ItemColumns::PROD_ID, 4);
            $table->string(ItemColumns::SKU, 50);
            $table->string(ItemColumns::NAME, 50);
            $table->string(ItemColumns::MEASUREMENT, 6);
            $table->integer(ItemColumns::BASE_PRICE)->default(0);
            $table->integer(ItemColumns::SELLING_PRICE)->default(0);
            $table->integer(ItemColumns::PURCHASE_UNIT)->default(30); #30 kode unit Pieces di tabel measurement_unit
            $table->integer(ItemColumns::SELL_UNIT)->default(30);
            $table->integer(ItemColumns::STOCK_UNIT)->default(0);
            $table->timestamps();
            $table->primary([ItemColumns::ID, ItemColumns::SKU]);
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
