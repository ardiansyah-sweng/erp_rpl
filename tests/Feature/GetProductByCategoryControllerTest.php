<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;

class GetProductByCategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_product_by_category_returns_paginated_products()
    {
        // Arrange: Buat 15 produk dengan category ID 1
        Product::factory()->count(15)->create([
            'product_category' => 1
        ]);

        // Act: Akses endpoint dengan ID kategori 1
        $response = $this->get('/products/category/1');

        // Assert: Periksa format JSON
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
            'current_page',
            'last_page',
            'per_page',
            'total'
        ]);

        // Pastikan isi halaman pertama adalah 10 item
        $this->assertCount(10, $response->json('data'));
    }
}
