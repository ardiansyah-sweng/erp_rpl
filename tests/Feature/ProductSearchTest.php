<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;

class ProductSearchTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // Seed data dummy untuk pengujian
        Product::create([
            'product_id' => 'P001',
            'product_name' => 'Laptop Asus',
            'product_type' => 'Elektronik',
            'product_category' => 1,
            'product_description' => 'Laptop dengan performa tinggi',
        ]);

        Product::create([
            'product_id' => 'P002',
            'product_name' => 'Meja Belajar',
            'product_type' => 'Furniture',
            'product_category' => 2,
            'product_description' => 'Meja belajar minimalis',
        ]);
    }

    /** @test */
    public function it_returns_products_that_match_search_query()
    {
        $response = $this->get('/product/search?search=Laptop');

        $response->assertStatus(200);
        $response->assertSee('Laptop Asus');
        $response->assertDontSee('Meja Belajar');
    }

    /** @test */
    public function it_returns_all_products_when_no_search_query_is_provided()
    {
        $response = $this->get('/product/search');

        $response->assertStatus(200);
        $response->assertSee('Laptop Asus');
        $response->assertSee('Meja Belajar');
    }
}
