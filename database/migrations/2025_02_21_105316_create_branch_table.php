<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Constants\BranchColumns;

return new class extends Migration
{
    protected string $table;

    public function __construct()
    {
        $this->table = config('db_tables.branch');
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->id();
<<<<<<< HEAD
            $table->string(BranchColumns::NAME, 50);
            $table->string(BranchColumns::ADDRESS, 100);
            $table->string(BranchColumns::PHONE, 30);
=======
            $table->string(BranchColumns::NAME, 50)->unique();
            $table->string(BranchColumns::ADDRESS, 100)->nullable();
            $table->string(BranchColumns::PHONE, 30)->nullable();
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
            $table->boolean(BranchColumns::IS_ACTIVE)->default(true);
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
