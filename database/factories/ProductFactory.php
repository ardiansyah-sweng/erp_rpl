<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'product_id' => $this->faker->uuid,
            'product_name' => $this->faker->word,
            'product_type' => $this->faker->randomElement(['FG', 'RM', 'HFG']),
            'product_category' => 1,
            'product_description' => $this->faker->sentence,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
