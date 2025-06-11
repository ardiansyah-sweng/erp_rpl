<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class GetProductByTypeTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        DB::table('products')->insert([
            [
                'product_id' => 'P001',
                'product_name' => 'Test FG',
                'product_type' => 'FG',
                'product_category' => 1,
                'product_description' => 'Description FG',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 'P002',
                'product_name' => 'Test RM',
                'product_type' => 'RM',
                'product_category' => 2,
                'product_description' => 'Description RM',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 'P003',
                'product_name' => 'Test HFG',
                'product_type' => 'HFG',
                'product_category' => 3,
                'product_description' => 'Description HFG',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }

    public function test_get_product_by_type_fg_returns_data()
    {
        $response = $this->get('/products/type/FG');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'product_type' => 'FG',
        ]);
    }

    public function test_get_product_by_type_rm_returns_data()
    {
        $response = $this->get('/products/type/RM');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'product_type' => 'RM',
        ]);
    }

    public function test_get_product_by_type_hfg_returns_data()
    {
        $response = $this->get('/products/type/HFG');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'product_type' => 'HFG',
        ]);
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
