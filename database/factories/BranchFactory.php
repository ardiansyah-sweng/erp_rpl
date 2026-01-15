<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Constants\BranchColumns;
use Illuminate\Database\Eloquent\Factories\Factory;

class BranchFactory extends Factory
{
    protected $model = Branch::class;

    public function definition(): array
    {
        return [
            BranchColumns::NAME => 'Cabang ' . $this->faker->company,
            BranchColumns::ADDRESS => $this->faker->address,
            BranchColumns::PHONE => $this->faker->phoneNumber,
            BranchColumns::IS_ACTIVE => $this->faker->boolean(80), // 80% chance active
        ];
    }

    /**
     * Indicate that the branch is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            BranchColumns::IS_ACTIVE => 1,
        ]);
    }

    /**
     * Indicate that the branch is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            BranchColumns::IS_ACTIVE => 0,
        ]);
    }
}