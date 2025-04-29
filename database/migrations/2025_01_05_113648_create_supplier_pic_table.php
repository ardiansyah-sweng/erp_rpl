<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function __construct()
    {
        $this->table = config('db_constants.table.supplier_pic');
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $col = config('db_constants.column.supplier_pic');

        Schema::create($this->table, function (Blueprint $table) use ($col) {
            $table->id();
            $table->char($col['supplier_id'], 6);
            $table->string($col['name'], 50);
            $table->string($col['phone_number'], 30);
            $table->string($col['email'], 50);
            $table->boolean($col['active'])->default(1);
            $table->string($col['avatar'], 100);
            $table->date($col['assigned_date']);
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
