<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;

class GetProductByCategoryControllerTest extends TestCase
{
    // Test untuk kategori yang ada
    public function testReturnsProductsByCategoryWhenExists()
    {
        $existingProduct = Product::whereNotNull('product_category')->first();

        if (!$existingProduct) {
            $this->markTestSkipped('Tidak ada produk dengan kategori di database.');
        }

        $categoryId = $existingProduct->product_category;

        $response = $this->getJson("/products/category/{$categoryId}");

        // Lihat isi respons (sementara debug)
        // dd($response->getContent());

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'success' => true,
                     'message' => 'Produk berdasarkan kategori ditemukan.',
                 ]);
    }

    // Test untuk kategori yang tidak ada
    public function testReturnsNotFoundWhenCategoryNotExists()
    {
        // Ambil ID kategori terbesar dan tambah nilai agar dijamin tidak ada
        $maxId = Product::max('product_category');
        $unusedCategoryId = $maxId + 999;

        $response = $this->getJson("/products/category/{$unusedCategoryId}");

        // Uncomment untuk debugging:
        // dd($response->getContent());

        $response->assertStatus(404)
                 ->assertJsonFragment([
                     'success' => false,
                     'message' => 'Tidak ada produk untuk kategori tersebut.',
                 ]);
    }
}
