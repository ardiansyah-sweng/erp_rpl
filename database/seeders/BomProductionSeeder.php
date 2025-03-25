<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BomProduction;
use App\Models\Item;
use App\Models\BillOfMaterial;
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
                            
        foreach ($res as $data)
        {
            print_r($data->sku);
            echo "\n";
        }

    }
}
