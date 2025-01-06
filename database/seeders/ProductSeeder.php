<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
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
        $numOfCategory = $this->faker->numberBetween(5, 100);
        $numOfProduct = $this->faker->numberBetween(5, 100);

        $this->createCategory($numOfCategory);
        
        $prefix = 'PRD';
        $measurement_unit = ['Ons', 'Mg', 'Kg', 'Unit', 'Pcs', 'Sheet', 'Lusin'];

        for ($i=1; $i <= $numOfProduct; $i++)
        {
            $formattedNumber = str_pad($i, 3, '0', STR_PAD_LEFT);

            Product::create([
                'id' => $prefix.$formattedNumber,
                'name' => $this->faker->word(),
                'category_id' => $this->faker->numberBetween(1, $numOfCategory),
                'description' => $this->faker->sentence(),
                'measurement_unit' => $this->faker->randomElement($measurement_unit)
            ]);
        }
    }

    public function createCategory($numOfCategory)
    {
        for ($i=1; $i <= $numOfCategory; $i++)
        {
            Category::create([
                'category' => $this->faker->word
            ]);
        }
    }
}
