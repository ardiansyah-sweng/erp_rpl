<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition(): array
    {
        return [
            'product_id' => 1,
            'sku' => $this->faker->unique()->word(),
            'item_name' => $this->faker->word(),
            'measurement_unit' => 1,
            'avg_base_price' => 10000,
            'selling_price' => 15000,
            'purchase_unit' => 'box',
            'sell_unit' => 'pcs',
            'stock_unit' => 'pcs',
        ];
    }
}
