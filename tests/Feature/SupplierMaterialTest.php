<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\SupplierMaterial;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SupplierMaterialTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_add_supplier_material_berhasil()
    {
        $data = [
            'supplier_id'  => 'S1',
            'company_name' => 'Supplier Satu',
            'product_id'   => 'P1',
            'product_name' => 'Product Satu',
            'base_price'   => 50000,
        ];

        $result = SupplierMaterial::addSupplierMaterial($data);

        $this->assertNotNull($result);
        $this->assertDatabaseHas('supplier_product', [
            'supplier_id' => 'S1',
            'base_price'  => 50000
        ]);
    }

    public function test_get_supplier_material_list()
    {
        SupplierMaterial::create([
            'supplier_id'  => 'S1',
            'company_name' => 'Supplier Satu',
            'product_id'   => 'P1',
            'product_name' => 'Product Satu',
            'base_price'   => 100,
        ]);

        $response = $this->get('/supplier/material');

        $response->assertStatus(200);
        $response->assertViewIs('supplier.material.list');
    }

    public function test_get_supplier_material_by_id()
    {
        $material = SupplierMaterial::create([
            'supplier_id'  => 'S1',
            'company_name' => 'Supplier Satu',
            'product_id'   => 'P1',
            'product_name' => 'Product Satu',
            'base_price'   => 10000
        ]);

        $response = $this->get('/supplier/material/' . $material->id);

        $response->assertStatus(200);
        $this->assertEquals($material->id, $response->viewData('material')->id);
    }

    /** @test */
    public function it_returns_empty_for_non_existing_supplier()
    {
        $results = SupplierMaterial::getSupplierMaterialByCategory(1, 'S999');
        $this->assertEmpty($results);
    }

    /** @test */
    public function it_returns_empty_for_invalid_category()
    {
        $results = SupplierMaterial::getSupplierMaterialByCategory(999, 'S1');
        $this->assertEmpty($results);
    }
}