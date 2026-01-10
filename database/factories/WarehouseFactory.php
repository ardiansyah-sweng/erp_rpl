<?php

namespace Database\Factories;

use App\Models\Warehouse;
use App\Constants\WarehouseColumns;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Warehouse>
 */
class WarehouseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Warehouse::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generate shorter company name to fit 50 character limit
        $companyName = $this->faker->unique()->words(2, true); // Max 2 words, unique
        $warehouseName = ucwords($companyName) . ' WH'; // Add 'WH' instead of 'Warehouse'

        return [
            WarehouseColumns::NAME => substr($warehouseName, 0, 50), // Ensure max 50 chars
            WarehouseColumns::ADDRESS => $this->faker->address(),
            WarehouseColumns::PHONE => $this->faker->phoneNumber(),
            WarehouseColumns::IS_RM_WAREHOUSE => $this->faker->boolean(50), // 50% chance true
            WarehouseColumns::IS_FG_WAREHOUSE => $this->faker->boolean(50), // 50% chance true
            WarehouseColumns::IS_ACTIVE => $this->faker->boolean(80), // 80% chance active
        ];
    }

    /**
     * Indicate that the warehouse is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            WarehouseColumns::IS_ACTIVE => true,
        ]);
    }

    /**
     * Indicate that the warehouse is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            WarehouseColumns::IS_ACTIVE => false,
        ]);
    }

    /**
     * Indicate that the warehouse is for raw materials.
     */
    public function rawMaterial(): static
    {
        return $this->state(fn (array $attributes) => [
            WarehouseColumns::IS_RM_WAREHOUSE => true,
            WarehouseColumns::IS_FG_WAREHOUSE => false,
        ]);
    }

    /**
     * Indicate that the warehouse is for finished goods.
     */
    public function finishedGoods(): static
    {
        return $this->state(fn (array $attributes) => [
            WarehouseColumns::IS_RM_WAREHOUSE => false,
            WarehouseColumns::IS_FG_WAREHOUSE => true,
        ]);
    }

    /**
     * Indicate that the warehouse handles both raw materials and finished goods.
     */
    public function mixed(): static
    {
        return $this->state(fn (array $attributes) => [
            WarehouseColumns::IS_RM_WAREHOUSE => true,
            WarehouseColumns::IS_FG_WAREHOUSE => true,
        ]);
    }

    /**
     * Create warehouse with specific name pattern.
     */
    public function withName(string $name): static
    {
        return $this->state(fn (array $attributes) => [
            WarehouseColumns::NAME => $name,
        ]);
    }

    /**
     * Create warehouse with specific location pattern.
     */
    public function inCity(string $city): static
    {
        return $this->state(fn (array $attributes) => [
            WarehouseColumns::NAME => substr($city . ' WH ' . $this->faker->randomNumber(3), 0, 50),
            WarehouseColumns::ADDRESS => $this->faker->streetAddress() . ', ' . $city,
        ]);
    }
}
