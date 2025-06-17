<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase; // This will reset the database after each test

    /**
     * Test searching categories with a keyword.
     *
     * @return void
     */
    public function test_search_category_with_keyword()
    {
        // Create categories for testing
        Category::create([
            'category' => 'Electronics',
            'parent_id' => null,
            'active' => 1,
        ]);

        Category::create([
            'category' => 'Home Electronics',
            'parent_id' => 1,
            'active' => 1,
        ]);

        Category::create([
            'category' => 'Clothing',
            'parent_id' => null,
            'active' => 1,
        ]);

        // Directly call the searchCategory method from the model
        $categories = Category::searchCategory('Electronics');

        // Ensure the correct categories are returned
        $this->assertCount(2, $categories);  // Two categories should be found: Electronics and Home Electronics
        $this->assertEquals('Electronics', $categories[0]->category);
        $this->assertEquals('Home Electronics', $categories[1]->category);
    }

    /**
     * Test searching categories with no results.
     *
     * @return void
     */
    public function test_search_category_no_results()
    {
        // Try searching for a non-existing category
        $categories = Category::searchCategory('NonExistingCategory');

        // Ensure no categories are found
        $this->assertCount(0, $categories);
    }
}
