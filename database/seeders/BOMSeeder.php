<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Product;
use App\Models\BillOfMaterial;
use App\Models\BOMDetail;
use Faker\Factory as Faker;

class BOMSeeder extends Seeder
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
        #hitung jumlah produk yang bertipe HFG (half finished goods)
        $HFG = Item::join('products', 'products.product_id', '=', 'item.product_id')
                        ->distinct()
                        ->where('products.product_type', 'HFG')
                        ->select('item.sku', 'item.measurement_unit');
        $HFGItemCount =  $HFG->count();

        #dapatkan item bertipe HFG secara acak
        $HFGItems = $HFG -> inRandomOrder()
                        ->limit($this->faker->numberBetween(1, $HFGItemCount))
                        ->get();

        $RM = Item::join('products', 'products.product_id', '=', 'item.product_id')
                        ->where('products.product_type', 'RM')
                        ->select('item.sku');

        $i = 1;
        foreach ($HFGItems as $item)
        {
            $RMCount = $RM->count();
            $RMItems = $RM -> inRandomOrder()
                            ->limit($this->faker->numberBetween(1, round($RMCount * 0.2)))
                            ->get();

            $bomID = 'BOM-'.str_pad($i, 3, '0', STR_PAD_LEFT);
            BillOfMaterial::create([
                'bom_id' => $bomID,
                'bom_name' => 'BOM-'.$item['sku'],
                'sku' => $item['sku'],
                'measurement_unit' => $item['measurement_unit'],
            ]);

            print_r($item['sku']);
            echo "\n";

            $total = 0;
            foreach ($RMItems as $rm)
            {
                $qty = $this->faker->numberBetween(1, 10);
                $cost = $this->faker->numberBetween(100, 5800);
                $total += $cost * $qty;
                BOMDetail::create([
                    'bom_id' => 'BOM-'.str_pad($i, 3, '0', STR_PAD_LEFT),
                    'sku' => $rm['sku'],
                    'quantity' => $qty,
                    'cost' => $cost,
                ]);
                print_r($rm);
                echo "\n";
            }

            BillOfMaterial::where('bom_id', $bomID)->update(['total_cost' => $total]);

            print_r($total);
            echo "\n";
            
            $i++;
        }
        dd();

        $numOfBOM = $this->faker->numberBetween(1, 10);

    }
}
