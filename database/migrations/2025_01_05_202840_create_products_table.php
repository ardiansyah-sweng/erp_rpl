<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $column = config('db_constants.column.products');
        Schema::create(config('db_constants.table.products'), function (Blueprint $table) use ($column) {
            $table->id();
            $table->char($column['id'], 36);
            $table->string($column['name'], 35);
            $table->string($column['type'], 12);
            $table->tinyInteger($column['category'],);
            $table->string($column['desc'], 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(config('db_constants.table.product'));
    }
};
