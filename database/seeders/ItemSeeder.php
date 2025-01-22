<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
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
        $column = config('db_constants.column.product');
        $finishedProduct = Product::take(13)->get();

        foreach ($finishedProduct as $data)
        {
            $numOfItemPerProduct = $this->faker->numberBetween(1, 10);
            $mou = $this->faker->randomElement(Measurement::cases())->value;

            for ($i=0; $i<=$numOfItemPerProduct; $i++)
            {
                $suffix = $this->faker->word();
                $sku = $data->{$column['id']}.'-'.$suffix;
                $itemName = $data->{$column['name']}.' '.$suffix;
                echo $sku.' '.$itemName.' '.$mou."\n";
            }
        }
    }
}
