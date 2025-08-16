<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class GetSupplierMaterialByProductTypeTest extends TestCase
{
    use WithoutMiddleware;

    public function test_get_data_with_valid_product_type()
    {
        $supplier_id = 'SUP001'; // Ganti sesuai ID supplier yang ada di DB kamu
        $product_type = 'FG'; // 'RM' atau 'HFG' jika tidak ada 'FG'

        $response = $this->getJson("/supplier-material/{$supplier_id}/{$product_type}");

        $response->assertStatus(200);

        // Pastikan respon berupa array JSON
        $this->assertIsArray($response->json());
    }

    public function test_get_data_with_invalid_product_type_returns_400()
    {
        $supplier_id = 1;
        $invalidProductType = 'ABC';

        $response = $this->getJson("/supplier-material/{$supplier_id}/{$invalidProductType}");

        $response->assertStatus(400);
        $response->assertJson([
            'error' => 'Invalid product type',
        ]);
    }
}
