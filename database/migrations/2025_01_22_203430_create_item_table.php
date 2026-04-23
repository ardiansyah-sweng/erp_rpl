<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
<<<<<<< HEAD

return new class extends Migration
{
=======
use App\Constants\ItemColumns;

return new class extends Migration
{
    protected string $table;

    public function __construct()
    {
        $this->table = config('db_tables.item');
    }

>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    /**
     * Run the migrations.
     */
    public function up(): void
    {
<<<<<<< HEAD
        $column = config('db_constants.column.item');
        $tableItem = config('db_constants.table.item');

        Schema::create($tableItem, function (Blueprint $table) use ($column) {
            $table->id();
            $table->char($column['prod_id'], 4);
            $table->string($column['sku'], 50);
            $table->string($column['name'], 50);
            $table->string($column['measurement'], 6);
            $table->integer($column['base_price'])->default(0);
            $table->integer($column['selling_price'])->default(0);
            $table->integer($column['purchase_unit'])->default(30); #30 kode unit Pieces di tabel measurement_unit
            $table->integer($column['sell_unit'])->default(30);
            $table->integer($column['stock_unit'])->default(0);
            $table->timestamps();
            $table->primary([$column['id'], $column['sku']]);
=======
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
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
<<<<<<< HEAD
        Schema::dropIfExists('item');
=======
        Schema::dropIfExists($this->table);
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    }
};
