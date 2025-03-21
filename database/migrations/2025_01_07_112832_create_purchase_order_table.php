<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function __construct()
    {
        $this->table = config('db_constants.table.po');
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $col = config('db_constants.column.po');

        Schema::create($this->table, function (Blueprint $table) use ($col) {
            $table->char($col['po_number'], 6);
            $table->char($col['supplier_id'], 6);
            $table->bigInteger($col['total']); #dinamis dari po detail
            $table->integer($col['branch_id']);
            $table->date($col['order_date']);
            $table->char($col['status'], 20)->default('Draft');
            $table->timestamps();

            $table->primary([$col['po_number'], $col['supplier_id']]);
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
