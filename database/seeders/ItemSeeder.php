<?php

namespace Database\Seeders;

use Generator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ConversionUnit;
use App\Models\Item;
use App\Models\Product;
use App\Models\MeasurementUnit;
use App\DataGeneration\SkripsiDatasetProvider;
use Faker\Factory as Faker;

class ItemSeeder extends Seeder
{
    public \Faker\Generator $faker;

    public function __construct()
    {
        $this->faker = Faker::create('id_ID');
        $this->faker->addProvider(new SkripsiDatasetProvider($this->faker));
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colProd = config('db_constants.column.products');
        $colItem = config('db_constants.column.item');
        $colUnit = config('db_constants.column.mu');
        $colCU   = config('db_constants.column.cu');

        $unitCombinations = [
            [['pieces', 1], ['lusin', 12], ['kodi', 20], ['gross', 144]],
            [['butir', 1], ['krat', 24]],
            [['botol', 1], ['lusin', 12], ['karton', 6]],
            [['bungkus', 1], ['slop', 10]],
            [['lembar', 1], ['rim', 500]],
            [['pack', 1], ['box', 12], ['karton', 6], ['dus', 24]],
            [['karung', 1], ['bal', 100]],
            [['drum', 1], ['pallet', 40], ['kontainer', 10]],
            [['gram', 1], ['kilogram', 1000], ['kuintal', 100000], ['ton', 1000000]],
            [['mililiter', 1], ['liter', 1000], ['galon', 19000]],
            [['miligram', 1], ['gram', 1000], ['ons', 100000]],
            [['strip', 1], ['box', 10]],
            [['sachet', 1], ['dus', 20], ['karton', 10]],
            [['batang', 1], ['ikat', 10]],
            [['pallet', 1], ['kontainer', 20]],
            [['orang', 1], ['tim', 5]],
        ];

        $products = Product::all();

        foreach ($products as $data)
        {
            $numOfItemPerProduct = $this->faker->numberBetween(1, 10);
            $unit = MeasurementUnit::inRandomOrder()->first();

            for ($i=0; $i<=$numOfItemPerProduct; $i++)
            {
                $unitID = $unit[$colUnit['id']];
                $unit = MeasurementUnit::where('id', $unitID)->first();
                $isUnitConversion = $this->faker->boolean();

                $suffix = $this->faker->word();
                $sku = $data->{$colProd['id']}.'-'.$suffix;
                $itemName = $data->{$colProd['name']}.' '.$suffix;

                Item::create([
                    $colItem['prod_id'] => $data->{$colProd['id']},
                    $colItem['sku'] => $sku,
                    $colItem['name'] => $itemName,
                    $colItem['measurement'] => $unitID,
                ]);

                if ($isUnitConversion)
                {
                    foreach ($unitCombinations as $units)
                    {
                        if ($units[0][0] == strtolower($unit->unit_name))
                        {
                            $sku = Item::where($colItem['sku'], $sku)->first();

                            print_r($units[0][0].' '.$unit->unit_name.' ');
                            print_r($units);
                            echo "\n";

                            foreach ($units as $key => $uc)
                            {
                                $res = MeasurementUnit::where('unit_name', $uc[0])->first();
                                print_r($res['id'].' ADA '.$uc[0]);
                                echo "\n";
                                print_r('==============');
                                echo "\n";
                                
                                $isBU = 0;
                                if ($key == 0)
                                {
                                    $isBU = 1;
                                }
                                ConversionUnit::create([
                                    $colCU['sku'] => $sku->{$colItem['sku']},
                                    $colCU['muid'] => $res['id'],
                                    $colCU['val'] => $uc[1],
                                    $colCU['isBU'] => $isBU
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }
}
