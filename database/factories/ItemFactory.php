<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\MeasurementUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    // DI SINI MASALAHNYA: Item.class -> Item::class
    protected $model = Item::class;

    public function definition(): array
    {
        return [
            'sku' => strtoupper($this->faker->unique()->bothify('??##-????')),
            
            'item_name' => $this->faker->words(3, true),
            
            'avg_base_price' => $this->faker->numberBetween(10000, 50000),
            'selling_price' => $this->faker->numberBetween(55000, 100000),

            'measurement_unit_id' => MeasurementUnit::inRandomOrder()->first()->id,
            'purchase_unit_id' => MeasurementUnit::inRandomOrder()->first()->id,
            'sell_unit_id' => MeasurementUnit::inRandomOrder()->first()->id,
            'stock_unit_id' => MeasurementUnit::inRandomOrder()->first()->id,
        ];
    }
}
