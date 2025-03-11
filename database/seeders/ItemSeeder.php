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
        $colProduct = config('db_constants.column.product');
        $colItem = config('db_constants.column.item');

        $finishedProduct = Product::all();

        foreach ($finishedProduct as $data)
        {
            $numOfItemPerProduct = $this->faker->numberBetween(1, 10);
            $mou = $this->faker->randomElement(Measurement::cases())->value;

            for ($i=0; $i<=$numOfItemPerProduct; $i++)
            {
                $suffix = $this->faker->word();
                $sku = $data->{$colProduct['id']}.'-'.$suffix;
                $itemName = $data->{$colProduct['name']}.' '.$suffix;

                Item::create([
                    $colItem['sku'] => $sku,
                    $colItem['name'] => $itemName,
                    $colItem['measurement'] => $mou,
                ]);
            }
        }
    }
}
