<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Constants\ItemColumns;

return new class extends Migration
{
    protected string $table;

    public function __construct()
    {
        $this->table = config('db_constants.table.item', 'items');
    }

    public function up(): void
    {
        Schema::table($this->table, function (Blueprint $table) {
            $table->integer(ItemColumns::MINIMUM_STOCK)->default(0)->after(ItemColumns::STOCK_UNIT);
        });
    }

    public function down(): void
    {
        Schema::table($this->table, function (Blueprint $table) {
            $table->dropColumn(ItemColumns::MINIMUM_STOCK);
        });
    }
};
