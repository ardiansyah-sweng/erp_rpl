<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SupplierPICModel;

class SupplierPICModelFactory extends Factory
{
    protected $model = SupplierPICModel::class;

    public function definition(): array
    {
        return [
            'supplier_id'   => 'SUP' . $this->faker->unique()->numberBetween(100, 999),
            'name'          => $this->faker->name(),
            'phone_number'  => $this->faker->phoneNumber(),
            'email'         => $this->faker->unique()->safeEmail(),
            'assigned_date' => now()->format('Y-m-d'),
        ];
    }
}