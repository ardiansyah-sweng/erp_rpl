<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Product;
use App\Enums\Measurement;
use Faker\Factory as Faker;


class ItemSeeder extends Seeder
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
        $colProd = config('db_constants.column.products');
        $colItem = config('db_constants.column.item');

        $products = Product::all();

        foreach ($products as $data)
        {
            $numOfItemPerProduct = $this->faker->numberBetween(1, 10);
            $mou = $this->faker->randomElement(Measurement::cases())->value;

            for ($i=0; $i<=$numOfItemPerProduct; $i++)
            {
                $suffix = $this->faker->word();
                $sku = $data->{$colProd['id']}.'-'.$suffix;
                $itemName = $data->{$colProd['name']}.' '.$suffix;

                Item::create([
                    $colItem['prod_id'] => $data->{$colProd['id']},
                    $colItem['sku'] => $sku,
                    $colItem['name'] => $itemName,
                    $colItem['measurement'] => $mou,
                ]);
            }
        }
    }
}
