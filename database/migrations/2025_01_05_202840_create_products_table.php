<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
<<<<<<< HEAD

return new class extends Migration
{
=======
use App\Constants\ProductColumns;

return new class extends Migration
{
    protected string $table;

    public function __construct()
    {
        $this->table = config('db_tables.product');
    }

>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    /**
     * Run the migrations.
     */
    public function up(): void
    {
<<<<<<< HEAD
        $column = config('db_constants.column.products');
        Schema::create(config('db_constants.table.products'), function (Blueprint $table) use ($column) {
            $table->id();
            $table->char($column['id'], 4);
            $table->string($column['name'], 35);
            $table->string($column['type'], 12);
            $table->tinyInteger($column['category'],);
            $table->string($column['desc'], 255);
=======
        Schema::create($this->table, function (Blueprint $table) {
            $table->id();
            $table->char(ProductColumns::PRODUCT_ID, 4)->unique();
            $table->string(ProductColumns::NAME, 35);
            $table->string(ProductColumns::TYPE, 12);
            $table->integer(ProductColumns::CATEGORY);
            $table->string(ProductColumns::DESC, 225)->nullable();
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
<<<<<<< HEAD
        Schema::dropIfExists(config('db_constants.table.product'));
=======
        Schema::dropIfExists($this->table);
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    }
};
