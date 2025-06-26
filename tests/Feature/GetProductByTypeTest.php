<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;

class GetProductByTypeTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function test_get_product_by_type_fg_returns_data()
    {
        $product = Product::where('product_type', 'FG')->first();
        $this->assertNotNull($product, 'Produk dengan tipe FG tidak ditemukan');

        $response = $this->get('/products/type/FG');
        $response->assertStatus(200);
        $response->assertJsonFragment(['product_type' => 'FG']);
    }

    public function test_get_product_by_type_rm_returns_data()
    {
        $product = Product::where('product_type', 'RM')->first();
        $this->assertNotNull($product, 'Produk dengan tipe RM tidak ditemukan');

        $response = $this->get('/products/type/RM');
        $response->assertStatus(200);
        $response->assertJsonFragment(['product_type' => 'RM']);
    }

    public function test_get_product_by_type_hfg_returns_data()
    {
        $product = Product::where('product_type', 'HFG')->first();
        $this->assertNotNull($product, 'Produk dengan tipe HFG tidak ditemukan');

        $response = $this->get('/products/type/HFG');
        $response->assertStatus(200);
        $response->assertJsonFragment(['product_type' => 'HFG']);
    }

    public function test_get_product_by_invalid_type_returns_404()
    {
        $response = $this->get('/products/type/XYZ');
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Tidak ditemukan produk dengan tipe tersebut: XYZ',
        ]);
    }
}
