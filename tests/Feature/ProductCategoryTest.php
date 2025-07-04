<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductCategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_products_by_category()
    {
        // Buat kategori baru (isi field 'category')
        $category = Category::create([
            'category' => 'Dessert' // Ganti sesuai nama kolom di tabel categories
        ]);

        // Buat dua produk yang pakai ID kategori tersebut
        Product::create([
            'product_id' => 'P001',
            'product_name' => 'Brownies Cokelat',
            'product_type' => 'FG',
            'product_category' => $category->id, // foreign key
            'product_description' => 'Cokelat lembut'
        ]);

        Product::create([
            'product_id' => 'P002',
            'product_name' => 'Brownies Keju',
            'product_type' => 'FG',
            'product_category' => $category->id,
            'product_description' => 'Dengan keju melt'
        ]);

        // Kirim request berdasarkan ID kategori
        $response = $this->get('/products/category/' . $category->id);

        // Cek hasilnya
        $response->assertStatus(200);
        $response->assertJsonFragment(['product_name' => 'Brownies Cokelat']);
        $response->assertJsonFragment(['product_name' => 'Brownies Keju']);
    }

    /** @test */
    public function it_returns_404_when_no_product_found()
    {
        $response = $this->get('/products/category/999'); // ID fiktif

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Produk dengan kategori tersebut tidak ditemukan'
        ]);
    }
}
