<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Category;

class CategoryControllerTest extends TestCase
{
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
