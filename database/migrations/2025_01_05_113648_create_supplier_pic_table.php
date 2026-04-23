<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
<<<<<<< HEAD

return new class extends Migration
{
    public function __construct()
    {
        $this->table = config('db_constants.table.supplier_pic');
=======
use App\Constants\SupplierPicColumns;

return new class extends Migration
{
    protected string $table;

    public function __construct()
    {
        $this->table = config('db_tables.supplier_pic');
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
<<<<<<< HEAD
        $col = config('db_constants.column.supplier_pic');

        Schema::create($this->table, function (Blueprint $table) use ($col) {
            $table->id();
            $table->char($col['supplier_id'], 6);
            $table->string($col['name'], 50);
            $table->string($col['phone_number'], 30);
            $table->string($col['email'], 50);
            $table->boolean($col['active'])->default(true);
            $table->string($col['avatar'], 100)->default('http://placehold.it/100x100');
            $table->date($col['assigned_date']);
=======
        Schema::create($this->table, function (Blueprint $table) {
            $table->id();
            $table->char(SupplierPicColumns::SUPPLIER_ID, 6);
            $table->string(SupplierPicColumns::NAME, 50)->nullable();
            $table->string(SupplierPicColumns::PHONE, 30)->nullable();
            $table->string(SupplierPicColumns::EMAIL, 50)->nullable();
            $table->boolean(SupplierPicColumns::IS_ACTIVE)->default(true);
            $table->string(SupplierPicColumns::AVATAR, 100)->default('http://placehold.it/100x100');
            $table->date(SupplierPicColumns::ASSIGNED_DATE)->nullable();
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
