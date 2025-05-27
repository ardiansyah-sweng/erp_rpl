<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Product;
use App\Models\BillOfMaterial;
use App\Models\BOMDetail;
use App\Models\AssortmentProduction;
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
        AssortmentProduction::truncate();

        #Tentukan jumlah BOM yang akan dibuat
        $bomCount = $this->faker->numberBetween(15, 20);

        #buat BOM
        $RM = Item::join('products', 'products.product_id', '=', 'item.product_id')
                        ->where('products.product_type', 'RM')
                        ->select('item.sku');

        $FG = Item::join('products', 'products.product_id', '=', 'item.product_id')
                        ->where('products.product_type', 'FG')
                        ->select('item.sku');                        

        for ($i = 1; $i <= $bomCount; $i++)
        {
            $RMCount = $RM->count();
            $RMItems = $RM -> inRandomOrder()
                            ->limit($this->faker->numberBetween(1, round($RMCount * 0.2)))
                            ->get();

            $bomID = 'BOM-'.str_pad($i, 3, '0', STR_PAD_LEFT);

            BillOfMaterial::create([
                'bom_id' => $bomID,
                'bom_name' => 'BOM-'.$bomID,
            ]);

            $total = 0;
            foreach ($RMItems as $rm)
            {
                $qty = $this->faker->numberBetween(1, 10);
                $cost = $this->faker->numberBetween(100, 5800);
                $total += $cost * $qty;
                BOMDetail::create([
                    'bom_id' => $bomID,
                    'sku' => $rm['sku'],
                    'quantity' => $qty,
                    'cost' => $cost,
                ]);
                print_r('rm'. $rm['sku']. ' qty: '.$qty.' cost: '.$cost);
                echo "\n";
            }

            BillOfMaterial::where('bom_id', $bomID)->update(['total_cost' => $total]);
        }
        // dd('BOM RM selesai dibuat');

        # Mulai Produksi Assortment BOM
        #-------------------------------------------------------------

        #Ambil jumlah produksi yang akan dibuat
        $prodCount = $this->faker->numberBetween(1, 50);

        for ($i=1; $i <= $prodCount; $i++)
        {
            #ambil BOM secara acak
            $bom = BillOfMaterial::inRandomOrder()->first();

            #buat nomor produksi
            $prodNo = 'PROD-'.str_pad($i, 3, '0', STR_PAD_LEFT);

            #buat tanggal produksi
            $prodDate = $this->faker->dateTimeBetween('-1 month', 'now');

            #buat jumlah produksi resep
            $bomQty = $this->faker->numberBetween(1, 100);

            #buat deskripsi produksi
            $desc = 'Produksi untuk BOM '.$bom->bom_id;

            #buat status produksi
            $inProduction = $this->faker->boolean();

            $skuFG = $FG -> inRandomOrder()->first();

            print_r('BOM ID: '.$bom->bom_id. ' | SKU: '.$skuFG->sku.
                    ' | Prod No: '.$prodNo.' | Prod Date: '.
                    ' | BOM Qty: '.$bomQty.' | In Production: '.($inProduction ? 'Yes' : 'No').
                    ' | Desc: '.$desc);
            echo "\n";

            #simpan data produksi
            AssortmentProduction::create([
                'production_number' => $prodNo,
                'sku' => $skuFG->sku,
                'production_date' => $prodDate,
                'bom_id' => $bom->bom_id,
                'bom_quantity' => $bomQty,
                'in_production' => $inProduction,
                'description' => $desc,
            ]);
        }
        dd('Produksi Assortment BOM selesai dibuat');

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

            print_r($bomID);
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
                print_r('rm'. $rm['sku']. ' qty: '.$qty.' cost: '.$cost);
                echo "\n";
            }

            BillOfMaterial::where('bom_id', $bomID)->update(['total_cost' => $total]);

            print_r($total);
            echo "\n";
            
            $i++;
        }
        //dd('BOM HFG selesai dibuat');

        #-------------------------------------------------------------
        #PERSIAPAN PEMBUATAN BOM UNTUK FG

        #ambil seluruh item yang berjenis FG
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
            #buat BOM ID berikutnya
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
            $subTotal = 0;
            if ($this->faker->boolean())
            {
                
                #ambil item HFG secara acak
                // $qHFG = Product::where('product_type', 'HFG')
                //                 ->inRandomOrder()
                //                 ->pluck('product_id')
                //                 ->take(1);

                #pilih BOM secara acak
                // $qBOM = DB::table('bill_of_material')
                //         ->select(DB::raw('LEFT(sku, 4) as prod_id'), 'bom_id', 'sku', 'total_cost');
                // $randomSKU = $qBOM -> inRandomOrder()->first();

                #ambil sku tabel bom_detail secara acak
                $skuBOMDetail = BOMDetail::inRandomOrder()
                        ->pluck('sku')
                        ->first();
                
                #temukan skuBOMDetail di tabel

                // while ($qHFG[0] != $randomSKU->prod_id)
                // {
                //     $randomSKU = $qBOM -> inRandomOrder()->first();
                // }

                $qty = $this->faker->numberBetween(1, 10);
                $cost = $qty * $randomSKU->total_cost;
                print_r($key.' '.'ADAAAA'.' '.$nextBOM_ID.' '.$randomSKU->sku.' '.$qty.' '.$cost);
                BOMDetail::create([
                    'bom_id' => $nextBOM_ID,
                    'sku' => $randomSKU->sku,
                    'quantity' => $qty,
                    'cost' => $cost,
                ]);
                $subTotal = $cost;
            }
            dd();
            // #insert RM ke BOMDetail
            // $RMItems = $RM -> inRandomOrder()
            //                 ->limit($this->faker->numberBetween(1, round($RMCount * 0.05)))
            //                 ->get();

            // $total = 0;
            // foreach ($RMItems as $rm)
            // {
            //     $qty = $this->faker->numberBetween(1, 10);
            //     $cost = $this->faker->numberBetween(100, 5800);
            //     $total += $cost * $qty;
                
            //     BOMDetail::create([
            //         'bom_id' => $nextBOM_ID,
            //         'sku' => $rm['sku'],
            //         'quantity' => $qty,
            //         'cost' => $cost,
            //     ]);
                
            //     #print_r($rm);
            //     echo "\n";
            // }
                
            // BillOfMaterial::where('bom_id', $nextBOM_ID)->update(['total_cost' => $total + $subTotal]);
        }
    }
}