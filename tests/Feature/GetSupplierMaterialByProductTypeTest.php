<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class GetSupplierMaterialByProductTypeTest extends TestCase
{
    use WithoutMiddleware;

    /** @test */
    public function it_returns_supplier_materials_for_valid_product_type()
    {
        // Setup data
        DB::table('products')->insert([
            'product_id' => 'KAOS',
            'product_type' => 'FG',
            'product_name' => 'Kaos Merah',
            'product_category' => '1',
            'product_description' => 'Kaos lengan pendek warna merah',
        ]);

        DB::table('item')->insert([
            'product_id' => 'KAOS',
            'item_name' => 'Kaos Merah',
            'measurement_unit' => 'pcs',
            'stock_unit' => 100,
            'sku' => 'KAOS-001',
        ]);

        DB::table('supplier_product')->insert([
            'supplier_id' => 'SUP001',
            'product_id' => 'KAOS-001', // ← substr 'KAOS' akan dicocokkan ke 'products.product_id'
            'product_name' => 'Kaos Merah',
            'company_name' => 'PT Uji Coba',
            'base_price' => 15000,
        ]);

        $response = $this->get('/supplier-material/SUP001/FG');

        $response->dump(); // debug

        $response->assertStatus(200);
        $this->assertNotEmpty($response->json(), 'Response kosong padahal data sudah dimasukkan.');

        $response->assertJsonStructure([
            '*' => [
                'supplier_id',
                'company_name',
                'product_id',
                'product_type',
                'base_price',
                'item_name',
                'measurement_unit',
                'stock_unit',
            ]
        ]);
    }

    /** @test */
    public function it_returns_400_for_invalid_product_type()
    {
        $response = $this->get('/supplier-material/SUP001/INVALID');
        $response->dump();
        $response->assertStatus(400);
    }
}
