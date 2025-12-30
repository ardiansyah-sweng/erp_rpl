<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function __construct()
    {
        $this->table = config('db_constants.table.assort_prod');
    }

    public function up(): void
    {
        $col = config('db_constants.column.assort_prod');

        Schema::create($this->table, function (Blueprint $table) use ($col) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';

            $table->id();
            $table->boolean('in_production')->nullable();
            $table->char($col['prod_no'], 9)->unique();
            $table->char($col['sku'], 50);
            $table->integer($col['branch']);
            $table->integer($col['rm_whouse']);
            $table->integer($col['fg_whouse']);
            $table->string($col['prod_date'], 45);
            $table->date($col['finished_date'])->nullable();
            $table->string($col['desc'], 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->table);
    }
};
