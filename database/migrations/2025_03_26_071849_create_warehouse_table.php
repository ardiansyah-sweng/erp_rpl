<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Constants\WarehouseColumns;

return new class extends Migration
{
    public function __construct()
    {
        $this->table = config('db_constants.table.whouse');
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->id();
            $table->string(WarehouseColumns::NAME, 50)->unique();
            $table->string(WarehouseColumns::ADDRESS, 100)->nullable();
            $table->string(WarehouseColumns::PHONE, 30)->nullable();
            $table->boolean(WarehouseColumns::IS_RM_WAREHOUSE)->default(false);
            $table->boolean(WarehouseColumns::IS_FG_WAREHOUSE)->default(false);
            $table->boolean(WarehouseColumns::IS_ACTIVE)->default(true);
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
