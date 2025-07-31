<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class GetSupplierMaterialByProductTypeTest extends TestCase
{
    use WithoutMiddleware;

    /** @test */
    public function it_returns_supplier_materials_for_valid_product_type()
    {
        // Gunakan data hardcoded sesuai instruksi reviewer
        $supplierId = 1; // Ganti sesuai dengan ID supplier yang valid di database kamu
        $productType = 'FG'; // 'RM', 'HFG', atau 'FG' sesuai data yang valid

        // Kirim request ke endpoint
        $response = $this->get("/supplier-material/{$supplierId}/{$productType}");

        // Validasi response
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'supplier_id',
                'company_name',
                'product_id',
                'product_name',
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
        $supplierId = 1; // Gunakan ID supplier valid (sama seperti di atas)
        $invalidType = 'XYZ'; // produk tidak valid

        // Kirim request ke endpoint dengan tipe produk tidak valid
        $response = $this->get("/supplier-material/{$supplierId}/{$invalidType}");

        // Validasi bahwa error ditangkap
        $response->assertStatus(400);
        $response->assertJson([
            'error' => 'Invalid product type',
        ]);
    }
}
