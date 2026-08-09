<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function __construct()
    {
        $this->tb_SupplierPic = config('db_constants.table.supplier_pic');
        $this->tb_Supplier = config('db_constants.table.supplier');
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table($this->tb_SupplierPic,
                      function (Blueprint $table) {
            $table->foreign('supplier_id')
                             ->references('supplier_id')
                             ->on($this->tb_Supplier)
                             ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table($this->tb_SupplierPic, 
                      function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
        });
    }
};
