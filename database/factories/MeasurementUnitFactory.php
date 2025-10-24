<?php

namespace Database\Factories;

use App\Models\MeasurementUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MeasurementUnit>
 */
class MeasurementUnitFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MeasurementUnit::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // FIX: Menggunakan nama kolom yang benar sesuai skema database: 'abbreviation'
            'unit_name' => $this->faker->unique()->word,
            'abbreviation' => strtoupper($this->faker->unique()->lexify('???')),
        ];
    }
}
