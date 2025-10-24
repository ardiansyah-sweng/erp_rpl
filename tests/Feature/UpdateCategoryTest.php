<?php

namespace Tests\Feature\Category;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers;

class UpdateCategoryTest extends TestCase
{
    /** @test */
    public function it_updates_a_category_successfully()
    {
        // Arrange: create a dummy category
        $response = $this->get('/category'); // Ensure the application is bootstrapped
        $category = Category::factory()->create([
            'category' => 'Ponsel & Aksesori',
            'parent_id' => 1,
            'active' => true,
        ]);

        // Act: send an update request
        $response = $this->put(route('category.update', $category->id), [
            'category' => 'Ponsel & Aksesori 2',
            'parent_id' => 1,
            'active' => false,
        ]);

        // Assert: check redirect and updated data
        $response->assertRedirect(route('category.edit', $category->id));
        $this->assertDatabaseHas('category', [
            'id' => $category->id,
            'category' => 'Ponsel & Aksesori 2',
            'active' => false,
        ]);
    }

    /** @test */
    public function it_returns_404_if_category_not_found()
    {
        // Act
        $response = $this->put(route('category.update', 9999), [
            'category' => 'Does Not Exist',
            'parent_id' => null,
            'active' => true,
        ]);

        // Assert
        $response->assertStatus(404);
        $response->assertJson(['message' => 'Kategori tidak ditemukan']);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        // Arrange
        $response = $this->get('/category'); // Ensure the application is bootstrapped
        $category = Category::factory()->create();

        // Act
        $response = $this->put(route('category.update', $category->id), []);

        // Assert: Laravel should redirect back with errors
        $response->assertSessionHasErrors(['category', 'active']);
    }
}
