<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\SupplierMaterial;

class SupplierMaterialCategoryControllerTest extends TestCase
{
    /**
     * Test controller getByCategory by comparing with model result
     */
    public function test_get_by_category_from_controller_matches_model()
    {
        $category = 'KAOS';
        $supplier = 'SUP001';

        // Panggil langsung ke model
        $expectedData = SupplierMaterial::getSupplierMaterialByCategory($category, $supplier);

        // Panggil ke endpoint controller
        $response = $this->getJson("/supplier-material/category?category={$category}&supplier={$supplier}");

        // Validasi HTTP response
        $response->assertStatus(200);

        $responseData = $response->json('data');

        // Pastikan jumlah data sama
        $this->assertCount($expectedData->count(), $responseData);

        // Optional: Cek apakah item pertama punya key yang diharapkan
        if (!empty($responseData)) {
            $this->assertArrayHasKey('supplier_id', $responseData[0]);
            $this->assertArrayHasKey('company_name', $responseData[0]);
            $this->assertArrayHasKey('product_id', $responseData[0]);
            $this->assertArrayHasKey('item_name', $responseData[0]);
        }
    }
}
