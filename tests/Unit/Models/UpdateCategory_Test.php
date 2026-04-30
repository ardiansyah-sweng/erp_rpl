<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;
use Faker\Factory as Faker;

class UpdateCategory_Test extends TestCase
{
    use RefreshDatabase;

    /**
     * Test updating an existing category.
     */
    public function test_update_existing_category()
    {
        $faker = Faker::create();

        // Create a category
        $category = Category::factory()->create();

        $newData = [
            'category' => $faker->word,
        ];

        $updatedCategory = Category::updateCategory($category->id, $newData);

        $this->assertNotNull($updatedCategory, 'Updated category should not be null');
        $this->assertEquals($newData['category'], $updatedCategory->category);
        $this->assertEquals($category->id, $updatedCategory->id);
    }

    /**
     * Test updating a non-existing category.
     */
    public function test_update_non_existing_category()
    {
        $faker = Faker::create();

        $newData = [
            'category' => $faker->word,
        ];

        $updatedCategory = Category::updateCategory(9999, $newData); // Assuming 9999 doesn't exist

        $this->assertNull($updatedCategory, 'Updated category should be null for non-existing category');
    }

    /**
     * Test updating category with invalid data (non-fillable fields).
     */
    public function test_update_category_with_invalid_data()
    {
        $faker = Faker::create();

        // Create a category
        $category = Category::factory()->create();

        $originalCategory = $category->category;

        $newData = [
            'category' => $faker->word,
            'invalid_field' => 'should not be updated',
        ];

        $updatedCategory = Category::updateCategory($category->id, $newData);

        $this->assertNotNull($updatedCategory);
        $this->assertEquals($newData['category'], $updatedCategory->category);
        $this->assertNotEquals($originalCategory, $updatedCategory->category);
        // Assuming 'invalid_field' is not fillable, it shouldn't be set
        $this->assertFalse(isset($updatedCategory->invalid_field));
    }
}

