<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Constants\SupplierPicColumns;

return new class extends Migration
{
    protected string $table;

    public function __construct()
    {
        $this->table = config('db_constants.table.supplier_pic');
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('DROP VIEW IF EXISTS `' . $this->table . '`');
        Schema::dropIfExists($this->table);

        Schema::create($this->table, function (Blueprint $table) {
            $table->id();
            $table->char(SupplierPicColumns::SUPPLIER_ID, 6);
            $table->string(SupplierPicColumns::NAME, 50)->nullable();
            $table->string(SupplierPicColumns::PHONE, 30)->nullable();
            $table->string(SupplierPicColumns::EMAIL, 50)->nullable();
            $table->boolean(SupplierPicColumns::IS_ACTIVE)->default(true);
            $table->string(SupplierPicColumns::AVATAR, 100)->default('http://placehold.it/100x100');
            $table->date(SupplierPicColumns::ASSIGNED_DATE)->nullable();
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
