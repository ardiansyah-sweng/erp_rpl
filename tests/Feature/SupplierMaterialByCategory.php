<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SupplierMaterialByCategory extends TestCase
{
    /** @test */
    public function it_returns_supplier_materials_for_valid_category_and_supplier()
    {
        $categoryId = 30; // ID kategori yang memang ada di tabel products
        $supplierId = 'SUP006'; // ID supplier yang sudah pasti ada

        $response = $this->get('/supplier-material/by-category?category_id=' . $categoryId . '&supplier_id=' . $supplierId);

        $response->assertStatus(200);

        $responseData = $response->json();

        $this->assertArrayHasKey('data', $responseData);
        $this->assertIsArray($responseData['data']);
        $this->assertNotEmpty($responseData['data']);

        foreach ($responseData['data'] as $item) {
            $this->assertEquals($supplierId, $item['supplier_id']);
            $this->assertEquals($categoryId, $item['product_category']);
        }

    }

    /** @test */
    public function it_returns_400_if_missing_parameters()
    {
        $response = $this->get('/supplier-material/by-category?supplier_id=SUP006');
        $response->assertStatus(400);

        $response = $this->get('/supplier-material/by-category?category_id=30');
        $response->assertStatus(400);
    }
}
