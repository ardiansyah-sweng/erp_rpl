<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Constants\ProductColumns;
use App\Constants\CategoryColumns;
use App\Enums\ProductType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class getProductByTypeTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup database tables
        $this->artisan('migrate');
    }

    /**
     * Helper method to create a test category
     */
    protected function createTestCategory($name = 'Test Category')
    {
        return Category::create([
            CategoryColumns::CATEGORY => $name,
            CategoryColumns::PARENT => null,
            CategoryColumns::IS_ACTIVE => true,
        ]);
    }

    /**
     * Helper method to create a test product with all required fields
     */
    protected function createTestProduct($productId, $name, $type, $categoryId, $description = 'Test Description')
    { 
        $product = new Product();
        $product->{ProductColumns::PRODUCT_ID} = $productId;
        $product->{ProductColumns::NAME} = $name;
        $product->{ProductColumns::TYPE} = $type;
        $product->{ProductColumns::CATEGORY} = $categoryId;
        $product->{ProductColumns::DESC} = $description;
        $product->save();
        
        return $product;
    }

    /**
     * Test getProductByType() returns all products with type FG (Finished Good)
     */
    public function test_get_product_by_type_returns_finished_good_products()
    {
        // Arrange - Create test category
        $category = $this->createTestCategory();

        // Create products with different types
        $this->createTestProduct('FG01', 'Finished Product 1', ProductType::FG->value, $category->{CategoryColumns::ID}, 'Test FG Product 1');
        $this->createTestProduct('FG02', 'Finished Product 2', ProductType::FG->value, $category->{CategoryColumns::ID}, 'Test FG Product 2');
        $this->createTestProduct('RM01', 'Raw Material 1', ProductType::RM->value, $category->{CategoryColumns::ID}, 'Test RM Product');

        // Act - Get products by type FG
        $result = Product::getProductByType(ProductType::FG);

        // Assert - Should return only FG products
        $this->assertNotNull($result);
        $this->assertCount(2, $result);
        
        foreach ($result as $product) {
            $this->assertEquals(ProductType::FG, $product->{ProductColumns::TYPE});
        }
        
        $productIds = $result->pluck(ProductColumns::PRODUCT_ID)->toArray();
        $this->assertContains('FG01', $productIds);
        $this->assertContains('FG02', $productIds);
        $this->assertNotContains('RM01', $productIds);
    }

    /**
     * Test getProductByType() returns all products with type RM (Raw Material)
     */
    public function test_get_product_by_type_returns_raw_material_products()
    {
        // Arrange - Create test category
        $category = $this->createTestCategory();

        // Create products with different types
        $this->createTestProduct('RM01', 'Raw Material 1', ProductType::RM->value, $category->{CategoryColumns::ID}, 'Test RM Product 1');
        $this->createTestProduct('RM02', 'Raw Material 2', ProductType::RM->value, $category->{CategoryColumns::ID}, 'Test RM Product 2');
        $this->createTestProduct('FG01', 'Finished Product 1', ProductType::FG->value, $category->{CategoryColumns::ID}, 'Test FG Product');

        // Act - Get products by type RM
        $result = Product::getProductByType(ProductType::RM);

        // Assert - Should return only RM products
        $this->assertNotNull($result);
        $this->assertCount(2, $result);
        
        foreach ($result as $product) {
            $this->assertEquals(ProductType::RM, $product->{ProductColumns::TYPE});
        }
        
        $productIds = $result->pluck(ProductColumns::PRODUCT_ID)->toArray();
        $this->assertContains('RM01', $productIds);
        $this->assertContains('RM02', $productIds);
        $this->assertNotContains('FG01', $productIds);
    }

    /**
     * Test getProductByType() returns all products with type HFG (Half Finished Goods)
     */
    public function test_get_product_by_type_returns_half_finished_goods_products()
    {
        // Arrange - Create test category
        $category = $this->createTestCategory();

        // Create products with different types
        $this->createTestProduct('HFG1', 'Half Finished Good 1', ProductType::HFG->value, $category->{CategoryColumns::ID}, 'Test HFG Product 1');
        $this->createTestProduct('HFG2', 'Half Finished Good 2', ProductType::HFG->value, $category->{CategoryColumns::ID}, 'Test HFG Product 2');
        $this->createTestProduct('RM03', 'Raw Material 1', ProductType::RM->value, $category->{CategoryColumns::ID}, 'Test RM Product');

        // Act - Get products by type HFG
        $result = Product::getProductByType(ProductType::HFG);

        // Assert - Should return only HFG products
        $this->assertNotNull($result);
        $this->assertCount(2, $result);
        
        foreach ($result as $product) {
            $this->assertEquals(ProductType::HFG, $product->{ProductColumns::TYPE});
        }
        
        $productIds = $result->pluck(ProductColumns::PRODUCT_ID)->toArray();
        $this->assertContains('HFG1', $productIds);
        $this->assertContains('HFG2', $productIds);
        $this->assertNotContains('RM03', $productIds);
    }

    /**
     * Test getProductByType() with no matching products
     */
    public function test_get_product_by_type_returns_empty_collection_when_no_matches()
    {
        // Arrange - Create test category and products with different type
        $category = $this->createTestCategory();

        // Create only RM product
        $this->createTestProduct('RM04', 'Raw Material 1', ProductType::RM, $category->{CategoryColumns::ID}, 'Test RM Product');

        // Act - Get products by type FG (but no FG products exist)
        $result = Product::getProductByType(ProductType::FG);

        // Assert - Should return empty collection
        $this->assertNotNull($result);
        $this->assertCount(0, $result);
        $this->assertTrue($result->isEmpty());
    }

    /**
     * Test getProductByType() with empty database
     */
    public function test_get_product_by_type_with_empty_database()
    {
        // Act - Get products by type with empty database
        $result = Product::getProductByType(ProductType::FG);

        // Assert - Should return empty collection
        $this->assertNotNull($result);
        $this->assertCount(0, $result);
        $this->assertTrue($result->isEmpty());
    }

    /**
     * Test getProductByType() returns all products of specific type regardless of other attributes
     */
    public function test_get_product_by_type_returns_all_matching_type_regardless_of_other_attributes()
    {
        // Arrange - Create multiple categories
        $category1 = $this->createTestCategory('Category 1');
        $category2 = $this->createTestCategory('Category 2');

        // Create FG products with different categories and descriptions
        $this->createTestProduct('FG03', 'Finished Product Alpha', ProductType::FG, $category1->{CategoryColumns::ID}, 'Description A');
        $this->createTestProduct('FG04', 'Finished Product Beta', ProductType::FG, $category2->{CategoryColumns::ID}, 'Description B');
        $this->createTestProduct('FG05', 'Finished Product Gamma', ProductType::FG, $category1->{CategoryColumns::ID}, null);

        // Act - Get all FG products
        $result = Product::getProductByType(ProductType::FG);

        // Assert - Should return all FG products regardless of category or description
        $this->assertNotNull($result);
        $this->assertCount(3, $result);
        
        foreach ($result as $product) {
            $this->assertEquals(ProductType::FG, $product->{ProductColumns::TYPE});
        }
    }

    /**
     * Test getProductByType() filters correctly when all product types exist
     */
    public function test_get_product_by_type_filters_correctly_with_mixed_types()
    {
        // Arrange - Create test category
        $category = $this->createTestCategory();

        // Create products of all types
        $this->createTestProduct('FG06', 'Finished Good', ProductType::FG->value, $category->{CategoryColumns::ID}, 'FG Product');
        $this->createTestProduct('RM05', 'Raw Material', ProductType::RM->value, $category->{CategoryColumns::ID}, 'RM Product');
        $this->createTestProduct('HFG3', 'Half Finished Good', ProductType::HFG->value, $category->{CategoryColumns::ID}, 'HFG Product');

        // Act & Assert for each type
        $fgResult = Product::getProductByType(ProductType::FG);
        $this->assertCount(1, $fgResult);
        $this->assertEquals('FG06', $fgResult->first()->{ProductColumns::PRODUCT_ID});

        $rmResult = Product::getProductByType(ProductType::RM);
        $this->assertCount(1, $rmResult);
        $this->assertEquals('RM05', $rmResult->first()->{ProductColumns::PRODUCT_ID});

        $hfgResult = Product::getProductByType(ProductType::HFG);
        $this->assertCount(1, $hfgResult);
        $this->assertEquals('HFG3', $hfgResult->first()->{ProductColumns::PRODUCT_ID});
    }

    /**
     * Test getProductByType() returns collection not paginated result
     */
    public function test_get_product_by_type_returns_collection_not_paginated()
    {
        // Arrange - Create test category and multiple products
        $category = $this->createTestCategory();

        // Create 4 FG products
        for ($i = 1; $i <= 4; $i++) {
            $productId = "FG" . str_pad($i, 2, '0', STR_PAD_LEFT);
            $this->createTestProduct($productId, "Finished Product {$i}", ProductType::FG->value, $category->{CategoryColumns::ID}, "Test Product {$i}");
        }

        // Act - Get products by type
        $result = Product::getProductByType(ProductType::FG);

        // Assert - Should return all products as collection, not paginated
        $this->assertNotNull($result);
        $this->assertCount(4, $result);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
        
        // Verify it's not a paginator instance
        $this->assertNotInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $result);
    }
}