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
        Schema::table('goods_returns', function (Blueprint $table) {
            // Menambahkan kolom bukti_lampiran setelah kolom reason
            $table->string('bukti_lampiran')->nullable()->after('reason');
        });
    }

    public function down(): void
    {
        Schema::table('goods_returns', function (Blueprint $table) {
            // Menghapus kolom jika kita melakukan rollback
            $table->dropColumn('bukti_lampiran');
        });
    }
};
