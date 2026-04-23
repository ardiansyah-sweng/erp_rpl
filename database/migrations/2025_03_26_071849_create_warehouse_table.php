<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
<<<<<<< HEAD

return new class extends Migration
{
    public function __construct()
    {
        $this->table = config('db_constants.table.whouse');
=======
use App\Constants\WarehouseColumns;

return new class extends Migration
{
    protected string $table;

    public function __construct()
    {
        $this->table = config('db_tables.warehouse');
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
<<<<<<< HEAD
        $col = config('db_constants.column.whouse');

        Schema::create($this->table, function (Blueprint $table) use ($col) {
            $table->id();
            $table->string($col['name'], 50);
            $table->string($col['address'], 100);
            $table->string($col['phone'], 30);
            $table->boolean($col['is_rm_whouse'])->default(false);
            $table->boolean($col['is_fg_whouse'])->default(false);
            $table->boolean($col['is_active'])->default(true);
=======
        Schema::create($this->table, function (Blueprint $table) {
            $table->id();
            $table->string(WarehouseColumns::NAME, 50)->unique();
            $table->string(WarehouseColumns::ADDRESS, 100)->nullable();
            $table->string(WarehouseColumns::PHONE, 30)->nullable();
            $table->boolean(WarehouseColumns::IS_RM_WAREHOUSE)->default(false);
            $table->boolean(WarehouseColumns::IS_FG_WAREHOUSE)->default(false);
            $table->boolean(WarehouseColumns::IS_ACTIVE)->default(true);
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
            $table->timestamps();
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
