<?php

namespace Database\Factories;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseFactory extends Factory
{
    protected $model = Warehouse::class;

    public function definition()
    {
        return [
            'warehouse_name' => $this->faker->company,
            'warehouse_address' => $this->faker->address,
            'warehouse_telephone' => $this->faker->phoneNumber,
            'is_rm_whouse' => $this->faker->boolean,
            'is_fg_whouse' => $this->faker->boolean,
            'is_active' => $this->faker->boolean,
        ];
    }
}
