<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Category;

class CategoryControllerTest extends TestCase
{

    public function test_get_category_by_parent_returns_correct_data()
    {
        // Arrange: Buat parent dan sub kategori
        $parent = Category::create(['category' => 'Parent', 'parent_id' => 0, 'active' => 1]);
        $child1 = Category::create(['category' => 'Sub 1', 'parent_id' => $parent->id, 'active' => 1]);
        $child2 = Category::create(['category' => 'Sub 2', 'parent_id' => $parent->id, 'active' => 1]);
        $other = Category::create(['category' => 'Other', 'parent_id' => 0, 'active' => 1]);

        // Act: akses endpoint
        $response = $this->getJson('/category/parent/' . $parent->id);

        // Assert: hanya kategori dengan parent_id sesuai yang muncul
        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $child1->id])
            ->assertJsonFragment(['id' => $child2->id])
            ->assertJsonMissing(['id' => $other->id]);
    }
}


    /** @test */
    public function test_it_returns_json_data_if_category_exists()
    {
        $category = Category::with('parent:id,category')->first();

        if (!$category) {
            $this->markTestSkipped('Tidak ada data kategori di database untuk diuji.');
            return;
        }

        $response = $this->get('/category/' . $category->id);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $category->id,
            'category' => $category->category,
        ]);
    }

    /** @test */
    public function test_it_returns_404_json_if_category_not_found()
    {
        $invalidId = (Category::max('id') ?? 0) + 100;

        $response = $this->get('/category/' . $invalidId);

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Category not found',
        ]);
    }
}

