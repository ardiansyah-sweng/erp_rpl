<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase; // Penting!
use Tests\TestCase;

class CategoryTest extends TestCase
{  
  
    public function test_it_can_delete_an_unused_category_successfully()
    {
        // Arrange
        $category = Category::factory()->create();

        // Act
        $result = Category::deleteCategoryById($category->id);

        // Assert
        $this->assertTrue($result);
        $this->assertModelMissing($category);
    }

    
    public function test_it_fails_to_delete_a_category_that_is_in_use_by_a_product()
    {
        // Arrange
        $category = Category::factory()->create();
        Product::factory()->create(['product_category' => $category->id]);

        // Act
        $result = Category::deleteCategoryById($category->id);

        // Assert
        $this->assertFalse($result);
        $this->assertModelExists($category);
    }

    
    public function test_it_returns_false_when_trying_to_delete_a_non_existent_category()
    {
        // Arrange
        $nonExistentId = 999;

        // Act
        $result = Category::deleteCategoryById($nonExistentId);

        // Assert
        $this->assertFalse($result);
    }
}