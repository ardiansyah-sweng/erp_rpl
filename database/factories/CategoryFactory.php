<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;
use App\DataGeneration\SkripsiDatasetProvider;
use App\Constants\CategoryColumns;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = Faker::create('id_ID');
        $datasetProvider = new SkripsiDatasetProvider($faker);

        return [
            CategoryColumns::CATEGORY => $datasetProvider->asssproductCategory(),
            CategoryColumns::PARENT => null, // Default tidak ada parent
            CategoryColumns::IS_ACTIVE => $faker->boolean(80), // 80% chance active
        ];
    }

    /**
     * Indicate that the category should be active.
     */
    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                CategoryColumns::IS_ACTIVE => true,
            ];
        });
    }

    /**
     * Indicate that the category should be inactive.
     */
    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                CategoryColumns::IS_ACTIVE => false,
            ];
        });
    }

    /**
     * Create a category with specific parent.
     */
    public function withParent($parentId)
    {
        return $this->state(function (array $attributes) use ($parentId) {
            return [
                CategoryColumns::PARENT => $parentId,
            ];
        });
    }
}
