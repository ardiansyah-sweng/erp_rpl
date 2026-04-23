<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
<<<<<<< HEAD
use Illuminate\Support\Str;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Product>
=======

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
<<<<<<< HEAD
            'product_id' => strtoupper(Str::random(4)),
            'product_name' => $this->faker->word,
            'product_type' => $this->faker->randomElement(['FG', 'RM', 'HFG']),
            'product_category' => 1,
            'product_description' => $this->faker->sentence,
            'created_at' => now(),
            'updated_at' => now(),
=======
            'product_id'  => strtoupper($this->faker->unique()->lexify('????')),
            'name'        => $this->faker->word,
            'type'        => 'RM', // default type RM
            'category'    => 1,
            'description' => $this->faker->sentence,
            'created_at'  => now(),
            'updated_at'  => now(),
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
        ];
    }
}
