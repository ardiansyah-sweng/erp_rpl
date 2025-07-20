<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class GetProductByCategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function can_get_products_by_category_through_controller(): void
    {
        // Arrange : Buat data produk dummy dengan kategori tertentu
        $category = 1;
        Product::factory()->count(5)->create([
            'product_category' => $category
        ]);

        // Act : Kirim request GET ke route controller
        $response = $this->get("/products/category/{$category}");

        // Assert : Status sukses dan isi data sesuai
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',         // karena pakai paginate
            'current_page',
            'last_page',
            'per_page',
            'total'
        ]);
        $response->assertJsonCount(5, 'data');

        // Pastikan semua produk dalam response punya kategori yang sesuai
        foreach ($response->json('data') as $product) {
            $this->assertEquals($category, $product['product_category']);
        }
    }
}
