<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Constants\SupplierColumns;

return new class extends Migration
{
    public function __construct()
    {
        $this->table = config('db_tables.supplier');
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->char(SupplierColumns::SUPPLIER_ID, 6)->unique();
            $table->string(SupplierColumns::COMPANY_NAME, 100);
            $table->string(SupplierColumns::ADDRESS, 100);
            $table->string(SupplierColumns::PHONE, 30);
            $table->string(SupplierColumns::BANK_ACCOUNT, 100);
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
