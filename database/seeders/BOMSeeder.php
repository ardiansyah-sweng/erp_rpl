<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Product;
use App\Models\BillOfMaterial;
use App\Models\BOMDetail;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

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
        BOMDetail::truncate();
        BillOfMaterial::truncate();

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
                #print_r($rm);
                echo "\n";
            }

            BillOfMaterial::where('bom_id', $bomID)->update(['total_cost' => $total]);

            print_r($total);
            echo "\n";
            
            $i++;
        }

        #PERSIAPAN PEMBUATAN BOM UNTUK FG

        #ambil seluruh item yang berjeni FG
        $qItemProdFG = Item::join('products', 'products.product_id', '=', 'item.product_id')
                        ->distinct()
                        ->where('products.product_type', 'FG')
                        ->select('item.sku');
        
        #tiap FG akan dibuat BOM nya
        $itemFG = $qItemProdFG->get();
        foreach ($itemFG as $key => $fg)
        {
            print_r('FG '.$fg->sku);
            echo "\n";
            #ambil ID terakhir BOM
            $lastId = BillOfMaterial::max('id');
            print_r('last ID '. $lastId);
            echo "\n";
            #buat ID berikutnya
            $nextID = $lastId + 1;
            $nextBOM_ID = 'BOM-'.str_pad($nextID, 3, '0', STR_PAD_LEFT);

            #buat nama BOM
            $bomName = 'BOM-'.$fg->sku;

            BillOfMaterial::create([
                'bom_id' => $nextBOM_ID,
                'bom_name' => $bomName,
                'sku' => $fg->sku,
                'measurement_unit' => $item['measurement_unit'],
            ]);

            #apakah FG ini menggunakan HFG atau tidak?
            if ($this->faker->boolean())
            {
                
                #ambil BOM yang itemnya berjenis HFG secara acak
                $qHFG = Product::where('product_type', 'HFG')
                                ->inRandomOrder()
                                ->pluck('product_id')
                                ->take(1);

                #pilih BOM secara acak yang sama dengan HFG terpilih
                $qBOM = DB::table('bill_of_material')
                        ->select(DB::raw('LEFT(sku, 4) as prod_id'), 'bom_id', 'sku', 'total_cost');
                $randomSKU = $qBOM -> inRandomOrder()->first();

                while ($qHFG[0] != $randomSKU->prod_id)
                {
                    $randomSKU = $qBOM -> inRandomOrder()->first();
                }

                $qty = $this->faker->numberBetween(1, 10);
                $cost = $qty * $randomSKU->total_cost;
                print_r($key.' '.'ADAAAA'.' '.$nextBOM_ID.' '.$randomSKU->sku.' '.$qty.' '.$cost);
                BOMDetail::create([
                    'bom_id' => $nextBOM_ID,
                    'sku' => $randomSKU->sku,
                    'quantity' => $qty,
                    'cost' => $cost,
                ]);
            }

            #insert RM ke BOMDetail
            $RMItems = $RM -> inRandomOrder()
                            ->limit($this->faker->numberBetween(1, round($RMCount * 0.05)))
                            ->get();

            $total = 0;
            foreach ($RMItems as $rm)
            {
                $qty = $this->faker->numberBetween(1, 10);
                $cost = $this->faker->numberBetween(100, 5800);
                $total += $cost * $qty;
                
                BOMDetail::create([
                    'bom_id' => $nextBOM_ID,
                    'sku' => $rm['sku'],
                    'quantity' => $qty,
                    'cost' => $cost,
                ]);
                
                #print_r($rm);
                echo "\n";
            }
                
            BillOfMaterial::where('bom_id', $nextBOM_ID)->update(['total_cost' => $total]);
        }
    }
}