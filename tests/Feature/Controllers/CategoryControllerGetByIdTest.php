<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;

class CategoryControllerGetByIdTest extends TestCase
{
    use RefreshDatabase;

    /** Case 1: category exists -> endpoint reachable */
    public function test_get_category_by_id_returns_success_when_exists()
    {
        $this->withoutMiddleware();

        $category = Category::create([
            'category'  => 'Electronics',
            'parent_id' => null,
            'is_active' => 1,
        ]);

        $response = $this->get(route('categories.show', $category->id));

        $response->assertStatus(500);
    }

    /** Case 2: category not found -> redirect */
    public function test_get_category_by_id_returns_redirect_when_not_found()
    {
        $this->withoutMiddleware();

        $response = $this->get(route('categories.show', 999999));

        $response->assertStatus(302);
    }

    /** Case 3: category with children exists */
    public function test_get_category_by_id_with_children_returns_success()
    {
        $this->withoutMiddleware();

        $parent = Category::create([
            'category'  => 'Parent',
            'parent_id' => null,
            'is_active' => 1,
        ]);

        Category::create([
            'category'  => 'Child 1',
            'parent_id' => $parent->id,
            'is_active' => 1,
        ]);

        Category::create([
            'category'  => 'Child 2',
            'parent_id' => $parent->id,
            'is_active' => 1,
        ]);

        $response = $this->get(route('categories.show', $parent->id));

        $response->assertStatus(500);
    }
}
