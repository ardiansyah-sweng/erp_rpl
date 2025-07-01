<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function __construct()
    {
        $this->table = config('db_constants.table.assort_prodetail');
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $col = config('db_constants.column.assort_prodetail');

        Schema::create($this->table, function (Blueprint $table) use ($col) {
            $table->id();
            $table->char($col['prod_no'], 9);
            $table->char($col['bom_id'], 7);
            $table->integer($col['bom_qty']);
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
