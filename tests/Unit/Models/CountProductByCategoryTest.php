<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CountProductByCategoryTest extends TestCase
{
    use RefreshDatabase;

    // Test Case 1: Count products by category with multiple categories returns correct counts
    public function test_countProductByCategory_with_multiple_categories_returns_correct_counts()
    {
        // Arrange: Create products in different categories
        DB::table('products')->insert([
            ['product_id' => 'P001', 'name' => 'Product 1', 'type' => 'FG', 'category' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 'P002', 'name' => 'Product 2', 'type' => 'FG', 'category' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 'P003', 'name' => 'Product 3', 'type' => 'FG', 'category' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Act
        $result = Product::countProductByCategory();

        // Assert
        $this->assertCount(2, $result); // Two categories
        $electronics = $result->where('product_category', 1)->first(); // Ganti 'product_category' dengan 'category'
        $clothing = $result->where('product_category', 2)->first();
        $this->assertEquals(2, $electronics->total);
        $this->assertEquals(1, $clothing->total);
    }

    // Test Case 2: Count products by category with no products returns empty collection
    public function test_countProductByCategory_with_no_products_returns_empty_collection()
    {
        // Arrange: No products created
        $result = Product::countProductByCategory();

        // Assert
        $this->assertCount(0, $result);
        $this->assertEmpty($result);
    }

    // Test Case 3: Count products by category with single category returns correct count
    public function test_countProductByCategory_with_single_category_returns_correct_count()
    {
        // Arrange: Create products in same category
        DB::table('products')->insert([
            ['product_id' => 'P001', 'name' => 'Product 1', 'type' => 'FG', 'category' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 'P002', 'name' => 'Product 2', 'type' => 'FG', 'category' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 'P003', 'name' => 'Product 3', 'type' => 'FG', 'category' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Act
        $result = Product::countProductByCategory();

        // Assert
        $this->assertCount(1, $result); // One category
        $electronics = $result->first();
        $this->assertEquals(1, $electronics->product_category);
        $this->assertEquals(3, $electronics->total);
    }
}