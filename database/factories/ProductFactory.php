<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'product_id'  => strtoupper($this->faker->unique()->lexify('????')),
            'name'        => $this->faker->word,
            'type'        => 'RM', // default type RM
            'category'    => 1,
            'description' => $this->faker->sentence,
            'created_at'  => now(),
            'updated_at'  => now(),
        ];
    }
}
