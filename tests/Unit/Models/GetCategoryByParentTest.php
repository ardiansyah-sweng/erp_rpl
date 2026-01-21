<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetCategoryByParentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_categories_with_existing_parent_id()
    {
        $parent = Category::create([
            'category'  => 'Parent Category',
            'parent_id' => null,
            'is_active' => 1,
        ]);

        Category::create([
            'category'  => 'Child Category 1',
            'parent_id' => $parent->id,
            'is_active' => 1,
        ]);

        Category::create([
            'category'  => 'Child Category 2',
            'parent_id' => $parent->id,
            'is_active' => 0, 
        ]);

        $response = $this->getJson("/categories/parent/{$parent->id}");

        $response->assertStatus(200);
        $response->assertJsonCount(2); 
    }

    /** @test */
    public function it_returns_404_for_non_existing_parent_id()
    {
        $invalidParentId = 99999;

        $response = $this->getJson("/categories/parent/{$invalidParentId}");

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Tidak ada kategori dengan parent ID tersebut',
        ]);
    }

    /** @test */
    public function it_returns_404_if_parent_exists_but_has_no_children()
    {
        $parent = Category::create([
            'category'  => 'Parent Without Children',
            'parent_id' => null,
            'is_active' => 1,
        ]);

        $response = $this->getJson("/categories/parent/{$parent->id}");

        $response->assertStatus(404);
    }

    /** @test */
    public function it_returns_404_if_parent_id_is_not_numeric()
    {
        $response = $this->getJson("/categories/parent/abc");

      
        $response->assertStatus(404);
    }
}
