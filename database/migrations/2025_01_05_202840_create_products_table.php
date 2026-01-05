<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Constants\ProductColumns;

return new class extends Migration
{
    protected string $table;

    public function __construct()
    {
        $this->table = config('db_tables.product');
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->id();
<<<<<<< HEAD
            $table->char(ProductColumns::PRODUCT_ID, 4)->unique()->nullable();
            $table->string(ProductColumns::NAME, 35)->nullable();
            $table->string(ProductColumns::TYPE, 12)->nullable();
            $table->integer(ProductColumns::CATEGORY)->nullable();
            $table->string(ProductColumns::DESC, 225)->nullable();
            $table->string('product_category')->nullable(); // <-- BARIS BARU DITAMBAHKAN DI SINI
            $table->text('product_description')->nullable(); // <-- TAMBAHKAN BARIS BARU INI
            $table->string('product_name')->nullable();      // <-- TAMBAHKAN BARIS BARU INI
            $table->string('product_type')->nullable();      // <-- TAMBAHKAN BARIS BARU INI
=======
            $table->char(ProductColumns::PRODUCT_ID, 4)->unique();
            $table->string(ProductColumns::NAME, 35);
            $table->string(ProductColumns::TYPE, 12);
            $table->integer(ProductColumns::CATEGORY);
            $table->string(ProductColumns::DESC, 225)->nullable();
>>>>>>> b0a6f6dd058f0e9f6847c11eb652e8aa2532deb4
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
