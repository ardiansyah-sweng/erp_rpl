<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use Illuminate\Support\Str;


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
            'name' => $this->faker->word, // kolom sebenarnya di database
            'type' => $this->faker->randomElement(['FG', 'RM', 'HFG']), 
            'category' => 1, 
            'description' => $this->faker->sentence, 
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
