<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function __construct()
    {
        $this->table = config('db_constants.table.log_matory');
    }
    
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $col = config('db_constants.column.log_matory');

        Schema::create($this->table, function (Blueprint $table) use ($col) {
            $table->id();
            $table->string($col['log_id'], 50); // Unique identifier for the log entry
            $table->string($col['sku'], 50); // ID of the material (bahan baku)
            $table->integer($col['old_stock'])->default(0); // Stock before the change
            $table->integer($col['new_stock'])->default(0); // Stock after the change

            $table->comment('Tabel ini menyimpan riwayat perubahan stok bahan baku (RM) untuk keperluan audit dan pelacakan perubahan stok bahan baku (RM). Tabel ini akan diupdate secara otomatis oleh sistem ketika ada transaksi Goods Receipt Note (GRN) yang terkait dengan Purchase Order (PO) untuk bahan baku dan Juga ketika ada transaksi produksi yang mengurangi stok bahan baku. Tabel ini bergantung dengan tabel material_inventory yang menyimpan stok bahan baku (RM) saat ini.');
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
