<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;

class GetCategoryByParentTest extends TestCase
{
    /** @test */
    public function it_returns_categories_with_given_parent_id()
    {
        // Arrange: Buat parent category
        $parent = Category::create([
            'category' => 'Parent Category',
            'parent_id' => 0,
            'active' => true
        ]);

        // Buat child categories
        $child1 = Category::create([
            'category' => 'Child 1',
            'parent_id' => $parent->id,
            'active' => true
        ]);

        $child2 = Category::create([
            'category' => 'Child 2',
            'parent_id' => $parent->id,
            'active' => true
        ]);

        // Act: Hit endpoint
        $response = $this->getJson("/category/parent/{$parent->id}");

        // Assert
        $response->assertStatus(200);
        $response->assertJsonCount(2); // Harus 2 kategori anak
        $response->assertJsonFragment(['category' => 'Child 1']);
        $response->assertJsonFragment(['category' => 'Child 2']);
    }

    /** @test */
    public function it_returns_404_if_no_categories_found()
    {
        $response = $this->getJson('/category/parent/999');

        $response->assertStatus(404);
        $response->assertJson(['message' => 'Tidak ada kategori dengan parent ID tersebut']);
    }
}
