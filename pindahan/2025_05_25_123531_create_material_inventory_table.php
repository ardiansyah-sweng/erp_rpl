<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function __construct()
    {
        $this->table = config('db_constants.table.matory');
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $col = config('db_constants.column.matory');
        
        Schema::create($this->table, function (Blueprint $table) use ($col) {
            $table->id();
            $table->string($col['sku'], 50);
            $table->integer($col['stock'])->default(0); // Stock saat ini
            $table->comment('Tabel ini khusus menyimpan stok bahan baku (RM) yang digunakan untuk produksi barang jadi (FG). Tabel ini akan diupdate secara otomatis oleh sistem ketika ada transaksi Goods Receipt Note (GRN) yang terkait dengan Purchase Order (PO) untuk bahan baku dan Juga ketika ada transaksi produksi yang mengurangi stok bahan baku. Jumlah stok bahan baku akan dihitung berdasarkan jumlah yang diterima dari GRN dan jumlah yang digunakan dalam proses produksi. Tabel ini tidak menyimpan informasi tentang barang jadi (FG) atau setengah jadi (HFG), hanya fokus pada bahan baku (RM). Tabel ini bergantung dengan tabel log_material_stocks yang menyimpan riwayat perubahan stok bahan baku (RM) untuk keperluan audit dan pelacakan perubahan stok bahan baku (RM).');
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
