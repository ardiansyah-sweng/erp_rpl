<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Ubah kolom name menjadi nullable jika ada
            if (Schema::hasColumn('products', 'name')) {
                $table->string('name')->nullable()->change();
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Kembalikan ke NOT NULL jika perlu (opsional)
            if (Schema::hasColumn('products', 'name')) {
                $table->string('name')->nullable(false)->change();
            }
        });
    }
};
