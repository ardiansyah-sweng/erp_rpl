<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Models\Category;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'product_id' => strtoupper(Str::random(4)),
            'product_name' => $this->faker->word,
            'product_type' => $this->faker->randomElement(['FG', 'RM', 'HFG']),
            'product_category' =>  Category::factory(),
            'product_description' => $this->faker->sentence,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
