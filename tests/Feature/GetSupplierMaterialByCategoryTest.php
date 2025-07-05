<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetSupplierMaterialByCategoryTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_get_supplier_material_by_category_returns_data_when_exists(): void
    {
        $category = 'RM';
        $supplierId = 'SUP001';
        $response = $this->getJson("/supplier-materials/{$category}/{$supplierId}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'category' => $category,
                'supplier_id' => $supplierId,
            ]);
    }

    public function test_get_supplier_material_by_category_returns_404_when_data_not_found(): void
    {
        $category = 'INVALID_CATEGORY';
        $supplierId = 'SUP999';

        $response = $this->getJson("/supplier-materials/{$category}/{$supplierId}");

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Tidak ada data material ditemukan untuk kategori dan supplier ini.',
            ]);
    }

}
