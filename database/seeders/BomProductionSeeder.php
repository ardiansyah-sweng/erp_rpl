<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BomProduction;
use App\Models\Item;
use App\Models\BillOfMaterial;
use App\Models\Warehouse;
use Carbon\Carbon;
use Faker\Factory as Faker;

class BomProductionSeeder extends Seeder
{
    public function __construct()
    {
        $this->faker = Faker::create('id_ID');
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BomProduction::truncate();

        #ambil seluruh item yang berjenis FG
        $qItemProdFG = Item::join('products', 'products.product_id', '=', 'item.product_id')
                        ->distinct()
                        ->where('products.product_type', 'FG')
                        ->select('item.sku');

        $res = $qItemProdFG -> inRandomOrder()
                            ->take($this->faker->numberBetween(1, $qItemProdFG->count()))
                            ->get();

        #menentukan tanggal produksi. Antara tanggal > 2 hari dari sekarang atau <= 2 hari dari tanggal sekarang
        # >2 tanggal skrng. Status in_production == false. Selain itu in_production == true.
        $start_date = Carbon::parse('2025-01-01');
        $today = Carbon::now(); // Tidak perlu mengonversi ke format string terlebih dahulu
        
        // Menghitung selisih hari antara $start_date dan $today
        $max_days = $start_date->diffInDays($today);

        // Mengambil tanggal produksi secara acak
        $productionDate = $start_date->copy()->addDays(rand(0, $max_days));

        // Hitung selisih hari antara $productionDate dan $today
        $days_difference = round($productionDate->diffInDays($today, false));

        $in_production = 0;
        // Menentukan status produksi
        if ($days_difference < 2) {
            $in_production = 1;
        }

        $qtyBOM = $this->faker->numberBetween(1, 10);

        foreach ($res as $key => $data)
        {
            $fg = $data->sku;
            
            $prodNo = 'PROD-'.str_pad($key, 4, '0', STR_PAD_LEFT);

            $qWhouse = Warehouse::inRandomOrder()->select('warehouse_name')->first();
            
            print_r($fg.' '.$qWhouse->warehouse_name.' '.$productionDate.' '.$in_production.' '.$days_difference, $max_days);
            echo "\n";
        }

    }
}
