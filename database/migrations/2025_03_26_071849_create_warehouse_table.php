<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
        $col = config('db_constants.column.whouse');

        Schema::create($this->table, function (Blueprint $table) use ($col) {
            $table->id();
            $table->string($col['name'], 50);
            $table->string($col['address'], 100);
            $table->string($col['phone'], 30);
            $table->boolean($col['is_active']);
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
