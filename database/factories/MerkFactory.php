<?php

namespace Database\Factories;

use App\Models\Merk;
use App\Constants\MerkColumns;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Merk>
 */
class MerkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Merk::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            MerkColumns::MERK => $this->faker->unique()->company() . ' Brand',
            MerkColumns::IS_ACTIVE => $this->faker->randomElement([true, true, true, false]), // 75% chance of being active
        ];
    }

    /**
     * Indicate that the merk is active.
     *
     * @return static
     */
    public function active()
    {
        return $this->state(fn (array $attributes) => [
            MerkColumns::IS_ACTIVE => true,
        ]);
    }

    /**
     * Indicate that the merk is inactive.
     *
     * @return static
     */
    public function inactive()
    {
        return $this->state(fn (array $attributes) => [
            MerkColumns::IS_ACTIVE => false,
        ]);
    }

    /**
     * Generate a specific merk name.
     *
     * @param string $name
     * @return static
     */
    public function withName(string $name)
    {
        return $this->state(fn (array $attributes) => [
            MerkColumns::MERK => $name,
        ]);
    }
}
