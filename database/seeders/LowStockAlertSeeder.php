<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LowStockAlertSeeder extends Seeder
{
    public function run(): void
    {
        $table = config('db_tables.item', 'items');

        // Item stok habis (stock=0, min=10) -> tampil sebagai "Habis"
        DB::table($table)->whereIn('id', [1, 2, 3])->update([
            'stock_unit'    => 0,
            'minimum_stock' => 10,
        ]);

        // Item stok rendah (stock < minimum) -> tampil sebagai "Stok Rendah"
        DB::table($table)->whereIn('id', [4, 5, 6])->update([
            'stock_unit'    => 3,
            'minimum_stock' => 10,
        ]);

        DB::table($table)->whereIn('id', [7, 8])->update([
            'stock_unit'    => 7,
            'minimum_stock' => 15,
        ]);

        // Item stok aman (stock > minimum) -> tidak tampil di low stock
        DB::table($table)->whereIn('id', [9, 10, 11, 12])->update([
            'stock_unit'    => 50,
            'minimum_stock' => 10,
        ]);

        // Semua item lain: set minimum_stock = 0 agar tidak masuk alert
        DB::table($table)->where('id', '>', 12)->update([
            'stock_unit'    => 100,
            'minimum_stock' => 0,
        ]);
    }
}
