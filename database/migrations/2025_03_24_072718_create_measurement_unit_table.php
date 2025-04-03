<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function __construct()
    {
        $this->table = config('db_constants.table.mu');
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $col = config('db_constants.column.mu');

        Schema::create($this->table, function (Blueprint $table) use ($col) {
            $table->id();
            $table->string($col['unit'], 15);
            $table->string($col['abbr'], 6);
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
