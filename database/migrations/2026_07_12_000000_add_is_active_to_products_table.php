<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add an operational status so a product can be archived without removal.
     */
    public function up(): void
    {
        Schema::table(config('db_tables.product', 'products'), function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('description');
        });
    }

    public function down(): void
    {
        Schema::table(config('db_tables.product', 'products'), function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
