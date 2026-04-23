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
    $table->boolean('in_production')->nullable(); // hilangkan default(null) saja
    $table->char($col['prod_no'], 9)->collation('utf8mb4_unicode_ci')->unique();
    $table->char($col['sku'], 50)->collation('utf8mb4_unicode_ci');
    $table->integer($col['branch']);
    $table->integer($col['rm_whouse']);
    $table->integer($col['fg_whouse']);
    $table->string($col['prod_date'], 45)->collation('utf8mb4_unicode_ci'); // ubah ke VARCHAR biar sesuai
    $table->date($col['finished_date'])->nullable(); // tidak perlu default(null)
    $table->string($col['desc'], 100)->collation('utf8mb4_unicode_ci');
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
