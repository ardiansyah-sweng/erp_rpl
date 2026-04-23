<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
<<<<<<< HEAD
=======
use App\Constants\SupplierColumns;
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d

return new class extends Migration
{
    public function __construct()
    {
<<<<<<< HEAD
        $this->table = config('db_constants.table.supplier');
=======
        $this->table = config('db_tables.supplier');
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
<<<<<<< HEAD
        $col = config('db_constants.column.supplier');

        Schema::create($this->table, function (Blueprint $table) use ($col) {
            $table->char($col['supplier_id'], 6)->primary();
            $table->string($col['company_name'], 100);
            $table->string($col['address'], 100);
            $table->string($col['phone_number'], 30);
            $table->string($col['bank_account'], 100);
=======
        Schema::create($this->table, function (Blueprint $table) {
            $table->char(SupplierColumns::SUPPLIER_ID, 6)->unique();
            $table->string(SupplierColumns::COMPANY_NAME, 100);
            $table->string(SupplierColumns::ADDRESS, 100);
            $table->string(SupplierColumns::PHONE, 30);
            $table->string(SupplierColumns::BANK_ACCOUNT, 100);
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
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
