<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\SupplierMaterial;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

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

    public function test_get_supplier_material_by_category()
    {

        $categoryId = 12;        // dari tabel categories (id = 12)
        $supplierId = 'SUP018';  // dari tabel supplier_product (supplier_id = SUP018)

        // Jalankan fungsi
        $results = SupplierMaterial::getSupplierMaterialByCategory($categoryId, $supplierId);

        // Pastikan hasil berupa collection
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $results);

        // Hasil tidak boleh kosong
        $this->assertNotEmpty($results, 'Hasil filter tidak boleh kosong.');

        // Periksa data pertama
        $first = $results->first();
        $this->assertNotNull($first->product_name ?? null, 'Product name tidak boleh null.');
        $this->assertNotNull($first->item_name ?? null, 'Item name tidak boleh null.');
        $this->assertEquals($supplierId, $first->supplier_id ?? $supplierId, 'Supplier ID harus sesuai.');
    }
}
