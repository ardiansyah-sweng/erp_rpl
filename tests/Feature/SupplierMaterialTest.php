<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\SupplierMaterial;

use PHPUnit\Framework\Attributes\Test;

class SupplierMaterialTest extends TestCase
{
    #[Test]
    public function get_by_kategory_only()
    {
        $data = SupplierMaterial::getSupplierMaterialByCategory('P001-qui');
        dump($data); // gunakan dump agar test tetap lanjut
        $this->assertNotEmpty($data); // pastikan data tidak kosong
    }

    #[Test]
    public function get_by_supplier_only()
    {
        $data = SupplierMaterial::getSupplierMaterialByCategory(null, 'Nutriplex Pack qui');
        dump($data);
        $this->assertNotEmpty($data);
    }

    #[Test]
    public function get_by_both()
    {
        $data = SupplierMaterial::getSupplierMaterialByCategory('P001-qui', 'Nutriplex Pack qui');
        dump($data);
        $this->assertNotEmpty($data);


class SupplierMaterialTest extends TestCase
{
    public function test_get_supplier_material_list()
    {
        $response = $this->get('/supplier/material');

        $response->assertStatus(200);
        $response->assertViewIs('supplier.material.list');
        $response->assertViewHas('materials');

        $materials = $response->viewData('materials');
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $materials);
        $this->assertNotEmpty($materials->items(), 'Materials collection should not be empty.');

        $first = $materials->first();
        $this->assertNotNull($first->product_name ?? null, 'First material product_name should not be null.');
        $this->assertNotNull($first->supplier_id ?? null, 'First material supplier_id should not be null.');
        $this->assertNotNull($first->company_name ?? null, 'First material company_name should not be null.');
    }

    public function test_get_supplier_material_by_id()
    {
        $material = SupplierMaterial::getSupplierMaterial()->first();

        if (!$material) {
            $this->markTestSkipped('No supplier material data found for testing.');
            return;
        }

        $response = $this->get('/supplier/material/' . $material->id);

        $response->assertStatus(200);
        $response->assertViewIs('supplier.material.detail');
        $response->assertViewHas('material');

         $materialFromView = $response->viewData('material');

        // Tampilkan isi data berdasarkan ID
        dump("Data dari /supplier/material/{$material->id}:", $materialFromView);

        $materialFromView = $response->viewData('material');
        $this->assertEquals($material->id, $materialFromView->id);

        $this->assertNotNull($materialFromView->product_name ?? null, 'Material product_name should not be null.');
        $this->assertNotNull($materialFromView->supplier_id ?? null, 'Material supplier_id should not be null.');
        $this->assertNotNull($materialFromView->company_name ?? null, 'Material company_name should not be null.');

    }
}
