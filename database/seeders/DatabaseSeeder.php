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
            ProductSeeder::class,
            ItemSeeder::class,
            SupplierSeeder::class,
            BranchSeeder::class,
            // PurchaseOrderSeeder::class,
            // ProductPriceSeeder::class
        ]);
    }
}
