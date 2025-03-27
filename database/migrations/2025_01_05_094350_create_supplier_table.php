<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function __construct()
    {
        $this->table = config('db_constants.table.supplier');
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $col = config('db_constants.column.supplier');

        Schema::create($this->table, function (Blueprint $table) use ($col) {
            $table->char($col['supplier_id'], 6)->primary();
            $table->string($col['company_name'], 100);
            $table->string($col['address'], 100);
            $table->string($col['phone_number'], 30);
            $table->string($col['bank_account'], 100);
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
