<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\Product;
use App\Models\MeasurementUnit;
use App\Constants\ItemColumns;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            ItemColumns::PROD_ID => function () {
                // Use existing product or create one
                return Product::inRandomOrder()->value('product_id') ?? Product::factory()->create()->product_id;
            },
            ItemColumns::SKU => $this->faker->unique()->regexify('[A-Z0-9]{8}'),
            ItemColumns::NAME => $this->faker->words(3, true),
            ItemColumns::MEASUREMENT => function () {
                // Use existing measurement unit or default to 1
                return MeasurementUnit::inRandomOrder()->value('id') ?? 1;
            },
            ItemColumns::BASE_PRICE => $this->faker->numberBetween(1000, 100000),
            ItemColumns::SELLING_PRICE => $this->faker->numberBetween(1500, 150000),
            ItemColumns::PURCHASE_UNIT => 30, // Default unit ID for pieces
            ItemColumns::SELL_UNIT => 30, // Default unit ID for pieces  
            ItemColumns::STOCK_UNIT => $this->faker->numberBetween(0, 1000),
        ];
    }

    /**
     * Create item with specific product_id
     */
    public function forProduct($productId)
    {
        return $this->state(function (array $attributes) use ($productId) {
            return [
                'product_id' => $productId,
            ];
        });
    }

    /**
     * Create item with zero stock
     */
    public function zeroStock()
    {
        return $this->state(function (array $attributes) {
            return [
                'stock_unit' => 0,
            ];
        });
    }

    /**
     * Create item with high stock
     */
    public function highStock()
    {
        return $this->state(function (array $attributes) {
            return [
                'stock_unit' => $this->faker->numberBetween(500, 2000),
            ];
        });
    }
}