<?php

namespace Database\Factories;

use App\Models\SupplierPICModel;
use App\Models\Supplier;
use App\Constants\SupplierPicColumns;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SupplierPICModel>
 */
class SupplierPICFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SupplierPICModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            SupplierPicColumns::SUPPLIER_ID => 'SUP001',
            SupplierPicColumns::NAME => $this->faker->name(),
            SupplierPicColumns::PHONE => $this->faker->phoneNumber(),
            SupplierPicColumns::EMAIL => $this->faker->unique()->safeEmail(),
            SupplierPicColumns::IS_ACTIVE => true,
            SupplierPicColumns::ASSIGNED_DATE => $this->faker->date('Y-m-d', '-1 year'),
        ];
    }

    /**
     * Indicate that the PIC is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            SupplierPicColumns::IS_ACTIVE => false,
        ]);
    }

    /**
     * Indicate that the PIC is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            SupplierPicColumns::IS_ACTIVE => true,
        ]);
    }

    /**
     * Set specific supplier ID.
     */
    public function forSupplier(string $supplierId): static
    {
        return $this->state(fn (array $attributes) => [
            SupplierPicColumns::SUPPLIER_ID => $supplierId,
        ]);
    }

    /**
     * Set specific assigned date.
     */
    public function assignedOn(string $date): static
    {
        return $this->state(fn (array $attributes) => [
            SupplierPicColumns::ASSIGNED_DATE => $date,
        ]);
    }

    /**
     * Create PIC with specific name.
     */
    public function withName(string $name): static
    {
        return $this->state(fn (array $attributes) => [
            SupplierPicColumns::NAME => $name,
        ]);
    }
}
