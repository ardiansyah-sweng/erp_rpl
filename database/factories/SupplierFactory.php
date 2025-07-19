<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SupplierFactory extends Factory
{
    public function definition(): array
    {
        return [
            'supplier_id'   => strtoupper(Str::random(6)),
            'company_name'  => $this->faker->company,
            'address'       => $this->faker->address,
            'phone_number'  => $this->faker->phoneNumber,
            'bank_account'  => $this->faker->bankAccountNumber,
            'created_at'    => now(),
            'updated_at'    => now(),
        ];
    }
}
