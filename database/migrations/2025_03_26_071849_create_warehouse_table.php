<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Constants\WarehouseColumns;

return new class extends Migration
{
    protected string $table;

    public function __construct()
    {
        // nama tabel ambil dari config
        $this->table = config('db_tables.warehouse');
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tableName = $this->table;

        Schema::create($tableName, function (Blueprint $table) {
            $table->id(WarehouseColumns::ID);
            $table->string(WarehouseColumns::NAME, 50)->unique();
            $table->string(WarehouseColumns::ADDRESS, 100)->nullable();
            $table->string(WarehouseColumns::PHONE, 30)->nullable();
            $table->boolean(WarehouseColumns::IS_RM_WAREHOUSE)->default(false);
            $table->boolean(WarehouseColumns::IS_FG_WAREHOUSE)->default(false);
            $table->boolean(WarehouseColumns::IS_ACTIVE)->default(true);
            $table->timestamp(WarehouseColumns::CREATED_AT)->useCurrent();
            $table->timestamp(WarehouseColumns::UPDATED_AT)->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableName = $this->table;

        Schema::dropIfExists($tableName);
    }
};
