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

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $col = config('db_constants.column.assort_prod');

        Schema::create($this->table, function (Blueprint $table) use ($col) {
            $table->id();
            $table->char($col['prod_no'], 9); //->collation('utf8mb4_unicode_ci')
            $table->char($col['sku'], 50);
            $table->integer($col['branch']);
            $table->integer($col['rm_whouse']);
            $table->integer($col['fg_whouse']);
            $table->date($col['prod_date']);
            $table->date($col['finished_date'])->default(null)->nullable();
            $table->char($col['in_production'], 1)->default('n');
            $table->string($col['desc'], 100);
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
