<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_categories_with_parent()
    {
        // Buat data dummy
        Category::factory()->count(5)->create();

        // Panggil endpoint
        $response = $this->get('/categories');

        // Pastikan status HTTP nya 200
        $response->assertStatus(200);

        // Pastikan ada field JSON yang sesuai
        $response->assertJsonStructure([
            '*' => ['id', 'category', 'created_at', 'updated_at', 'parent']
        ]);
    }
}
