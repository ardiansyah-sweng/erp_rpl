<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Supplier;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    public function definition(): array
    {
        return [
            'supplier_id'  => strtoupper($this->faker->unique()->bothify('??????')),
            'company_name' => $this->faker->company,
            'address'      => $this->faker->address,
            'telephone'    => $this->faker->phoneNumber,
            'bank_account' => $this->faker->bankAccountNumber,
            'created_at'   => now(),
            'updated_at'   => now(),
        ];
    }
}
