<?php

/** php artisan test Tests/Unit/Models/GetCategoryTest.php --filter=test_get_category_returns_all_categories **/
namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Category;
use App\Constants\CategoryColumns;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetCategoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    /**
     * Test getCategory() returns all categories with parent relationship
     */
    public function test_get_category_returns_all_categories()
    {
        // Arrange - Create parent category
        $parentCategory = Category::create([
            CategoryColumns::CATEGORY => 'Electronics',
            CategoryColumns::PARENT => null,
            CategoryColumns::IS_ACTIVE => true,
        ]);

        // Create child categories
        $childCategory1 = Category::create([
            CategoryColumns::CATEGORY => 'Smartphones',
            CategoryColumns::PARENT => $parentCategory->id,
            CategoryColumns::IS_ACTIVE => true,
        ]);

        $childCategory2 = Category::create([
            CategoryColumns::CATEGORY => 'Laptops',
            CategoryColumns::PARENT => $parentCategory->id,
            CategoryColumns::IS_ACTIVE => true,
        ]);

        // Act
        $result = Category::getCategory();

        // Assert
        $this->assertNotNull($result);
        $this->assertCount(3, $result);
        
        // Verify parent relationship is loaded
        $child = $result->firstWhere(CategoryColumns::CATEGORY, 'Smartphones');
        $this->assertNotNull($child->parent);
        $this->assertEquals('Electronics', $child->parent->{CategoryColumns::CATEGORY});
    }

    /**
     * Test getCategory() returns empty collection when no categories exist
     */
    public function test_get_category_returns_empty_collection()
    {
        // Act
        $result = Category::getCategory();

        // Assert
        $this->assertNotNull($result);
        $this->assertCount(0, $result);
        $this->assertTrue($result->isEmpty());
    }

    /**
     * Test getCategory() includes parent relationship data
     */
    public function test_get_category_includes_parent_relationship()
    {
        // Arrange
        $parent = Category::create([
            CategoryColumns::CATEGORY => 'Furniture',
            CategoryColumns::PARENT => null,
            CategoryColumns::IS_ACTIVE => true,
        ]);

        $child = Category::create([
            CategoryColumns::CATEGORY => 'Office Chairs',
            CategoryColumns::PARENT => $parent->id,
            CategoryColumns::IS_ACTIVE => true,
        ]);

        // Act
        $result = Category::getCategory();

        // Assert
        $childCategory = $result->firstWhere('id', $child->id);
        
        // Verify relationship is eager loaded (no additional query needed)
        $this->assertTrue($childCategory->relationLoaded('parent'));
        $this->assertNotNull($childCategory->parent);
        $this->assertEquals($parent->id, $childCategory->parent->id);
    }

    /**
     * Test getCategory() returns categories without parent (parent_id is null)
     */
    public function test_get_category_returns_categories_without_parent()
    {
        // Arrange - Create only parent categories
        Category::create([
            CategoryColumns::CATEGORY => 'Books',
            CategoryColumns::PARENT => null,
            CategoryColumns::IS_ACTIVE => true,
        ]);

        Category::create([
            CategoryColumns::CATEGORY => 'Clothing',
            CategoryColumns::PARENT => null,
            CategoryColumns::IS_ACTIVE => true,
        ]);

        // Act
        $result = Category::getCategory();

        // Assert
        $this->assertCount(2, $result);
        
        foreach ($result as $category) {
            $this->assertNull($category->parent);
            $this->assertNull($category->{CategoryColumns::PARENT});
        }
    }

    /**
     * Test getCategory() returns all categories including inactive ones
     */
    public function test_get_category_includes_inactive_categories()
    {
        // Arrange
        Category::create([
            CategoryColumns::CATEGORY => 'Active Category',
            CategoryColumns::PARENT => null,
            CategoryColumns::IS_ACTIVE => true,
        ]);

        Category::create([
            CategoryColumns::CATEGORY => 'Inactive Category',
            CategoryColumns::PARENT => null,
            CategoryColumns::IS_ACTIVE => false,
        ]);

        // Act
        $result = Category::getCategory();

        // Assert
        $this->assertCount(2, $result);
        
        $activeCategory = $result->firstWhere(CategoryColumns::CATEGORY, 'Active Category');
        $inactiveCategory = $result->firstWhere(CategoryColumns::CATEGORY, 'Inactive Category');
        
        $this->assertEquals(1, $activeCategory->{CategoryColumns::IS_ACTIVE});
        $this->assertEquals(0, $inactiveCategory->{CategoryColumns::IS_ACTIVE});
    }

    /**
     * Test getCategory() returns collection not paginated
     */
    public function test_get_category_returns_collection_not_paginated()
    {
        // Arrange - Create multiple categories
        for ($i = 1; $i <= 25; $i++) {
            Category::create([
                CategoryColumns::CATEGORY => "Category {$i}",
                CategoryColumns::PARENT => null,
                CategoryColumns::IS_ACTIVE => true,
            ]);
        }

        // Act
        $result = Category::getCategory();

        // Assert - Should return all records without pagination
        $this->assertCount(25, $result);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
    }

    /**
     * Test getCategory() with nested parent-child relationships
     */
    public function test_get_category_with_nested_relationships()
    {
        // Arrange - Create 3-level hierarchy
        $grandparent = Category::create([
            CategoryColumns::CATEGORY => 'Root Category',
            CategoryColumns::PARENT => null,
            CategoryColumns::IS_ACTIVE => true,
        ]);

        $parent = Category::create([
            CategoryColumns::CATEGORY => 'Sub Category',
            CategoryColumns::PARENT => $grandparent->id,
            CategoryColumns::IS_ACTIVE => true,
        ]);

        $child = Category::create([
            CategoryColumns::CATEGORY => 'Leaf Category',
            CategoryColumns::PARENT => $parent->id,
            CategoryColumns::IS_ACTIVE => true,
        ]);

        // Act
        $result = Category::getCategory();

        // Assert
        $this->assertCount(3, $result);
        
        $leafCategory = $result->firstWhere('id', $child->id);
        $this->assertNotNull($leafCategory->parent);
        $this->assertEquals('Sub Category', $leafCategory->parent->{CategoryColumns::CATEGORY});
        
        $subCategory = $result->firstWhere('id', $parent->id);
        $this->assertNotNull($subCategory->parent);
        $this->assertEquals('Root Category', $subCategory->parent->{CategoryColumns::CATEGORY});
    }
}