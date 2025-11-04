<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_child_categories_for_given_parent_id(): void
    {
        $parent = Category::factory()->create();
        $child1 = Category::factory()->create(['parent_id' => $parent->id]);
        $child2 = Category::factory()->create(['parent_id' => $parent->id]);

        $response = $this->getJson("/api/categories/parent/{$parent->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $child1->id])
                 ->assertJsonFragment(['id' => $child2->id]);
    }

    /** @test */
    public function it_returns_404_if_no_child_categories_found()
    {
        $parent = Category::factory()->create();

        $response = $this->getJson("/api/categories/parent/{$parent->id}");

        $response->assertStatus(404)
         ->assertJson([
             'message' => 'Tidak ada kategori dengan parent ID tersebut',
         ]);
    }
}