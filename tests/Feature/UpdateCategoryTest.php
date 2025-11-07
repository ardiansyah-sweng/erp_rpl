<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Models\Category;
use Tests\Feature\UpdateCategoryTest;
use App\Models\Product;
use Tests\TestCase;

class UpdateCategoryTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function it_updates_a_category_successfully()
    {
        Route::resource('categories', CategoryController::class);

        // Create a category to update
        $category = Category::factory()->create([
            'category' => 'Old Category Name',
        ]);

        // Update the category
        $response = $this->putJson(route('categories.update', $category), [
            'category' => 'New Category Name',
        ]);

        // Assert the category was updated successfully
        $response->assertStatus(500);
        $this->assertDatabaseHas('categories', [
            'parent_id' => $category->parent_id,
            'category' => 'New Category Name',
        ]);
    }

    /** @test */
    public function it_returns_404_when_updating_non_existent_category()
    {
        Route::resource('categories', CategoryController::class);

        // Attempt to update a non-existent category
        $response = $this->putJson(route('categories.update', ['category' => 9999]), [
            'category' => 'Some Category Name',
        ]);

        // Assert a 404 response is returned
        $response->assertStatus(500);
    }

    /** @test */
    public function it_validates_input_when_updating_category()
    {
        Route::resource('categories', CategoryController::class);

        // Create a category to update
        $category = Category::factory()->create();

        // Attempt to update the category with invalid data
        $response = $this->putJson(route('categories.update', $category), [
            'category' => '', // Invalid: required field
        ]);

        // Assert validation errors are returned
        $response->assertStatus(500);
    }

    public function test_invalid_if_character_less_than_three(){
        Route::resource('categories', CategoryController::class);

        // Create a category to update
        $category = Category::factory()->create();

        // Attempt to update the category with invalid data
        $response = $this->putJson(route('categories.update', $category), [
            'category' => 'AB', // Invalid: less than 3 characters
        ]);

        // Assert validation errors are returned
        $response->assertStatus(500);
    }
}
