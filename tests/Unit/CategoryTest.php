<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase; 
use Tests\TestCase;

class CategoryTest extends TestCase
{
      
    public function test_it_can_delete_an_unused_category_successfully()
    {
        // Arrange
        $category = Category::whereDoesntHave('products')->first();

        $this->assertNotNull($category, 'Tidak ditemukan kategori yang tidak digunakan.');

        // Act
        $result = Category::deleteCategoryById($category->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_it_fails_to_delete_a_category_that_is_in_use_by_a_product()
    {
        // Arrange
        $category = Category::whereHas('products')->first();
        $this->assertNotNull($category, 'Tidak ditemukan kategori yang sedang digunakan.');

        // Act
        $result = Category::deleteCategoryById($category->id);

        // Assert
        $this->assertFalse($result);
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_it_returns_false_when_trying_to_delete_a_non_existent_category()
    {
        // Arrange
        $nonExistentId = Category::max('id') + 1;

        // Act
        $result = Category::deleteCategoryById($nonExistentId);

        // Assert
        $this->assertFalse($result);
    }
}
