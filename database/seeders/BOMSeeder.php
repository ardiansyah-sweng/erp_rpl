<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\AssortmentProduction;
use App\Models\AssortmentProductionDetail;
use App\Models\BillOfMaterial;
use App\Models\BOMDetail;
use App\Models\Branch;
use App\Models\Item;
use App\Models\Product;
use App\Models\Warehouse;

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
        $prodCount = $this->faker->numberBetween(10, 100);

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
            $branch = Branch::inRandomOrder()->first();
            $rmWhouse = Warehouse::where('is_rm_whouse', true)
                            ->inRandomOrder()
                            ->first();
            $fgWhouse = Warehouse::where('is_fg_whouse', true)
                            ->inRandomOrder()
                            ->first();

            #ambil seluruh BOM
            $bomCount = BillOfMaterial::count();
            
            #buat produksi assortment detail
            for ($j=0; $j < $this->faker->numberBetween(1, $bomCount); $j++)
            {
                #ambil BOM secara acak
                $bom = BillOfMaterial::inRandomOrder()->first();

                #buat jumlah bom
                $bomQty = $this->faker->numberBetween(1, 100);

                #buat deskripsi
                $desc = 'Produksi Detail untuk BOM '.$bom->bom_id;

                print_r('Prod Detail No: '.$prodNo.' | BOM ID: '.$bom->bom_id.
                        ' | BOM Qty: '.$bomQty.' | Desc: '.$desc);
                echo "\n";

                AssortmentProductionDetail::create([
                    'production_number' => $prodNo,
                    'bom_id' => $bom->bom_id,
                    'bom_quantity' => $bomQty,
                    'description' => $desc,
                ]);
            }

            AssortmentProduction::create([
                'production_number' => $prodNo,
                'sku' => $skuFG->sku,
                'branch_id' => $branch->id,
                'rm_whouse_id' => $rmWhouse->id,
                'fg_whouse_id' => $fgWhouse->id,
                'production_date' => $prodDate,
                'in_production' => $inProduction,
                'description' => $desc,
            ]);
        }

        // updated in_production status ke false
        // $inProduction = AssortmentProduction::where('in_production', true)
        //     ->get();
        // foreach ($inProduction as $prod) {
        //     if ($this->faker->boolean())
        //     {
        //         print_r('Update In Production: '.$prod->production_number);
        //         echo "\n";
        //         // update status in_production ke false
        //         AssortmentProduction::where('production_number', $prod->production_number)
        //             ->update(['in_production' => false, 'finished_date' => now()]);
        //     }
        // }
        
        print_r('---SELESAI---');
    }
}