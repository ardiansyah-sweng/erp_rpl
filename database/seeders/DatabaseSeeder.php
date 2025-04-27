<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MerkSeeder::class,
            MeasurementUnitSeeder::class,
            WarehouseSeeder::class,
            ProductSeeder::class,
             ItemSeeder::class,
             SupplierSeeder::class,
             BranchSeeder::class,
             PurchaseOrderSeeder::class,
             GoodsReceiptNoteSeeder::class,
            #BOMSeeder::class
             ProductPriceSeeder::class
        ]);
    }
}
