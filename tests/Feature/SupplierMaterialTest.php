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
        // Ambil supplier_id & category_id yang ada di database
        $data = DB::table('supplier_product as sp')
            ->join('item as i', 'i.sku', '=', 'sp.product_id')
            ->join('products as p', 'p.product_id', '=', 'i.product_id')
            ->join('category as c', 'c.id', '=', 'p.product_category')
            ->select('sp.supplier_id', 'c.id as category_id')
            ->first();

        $this->assertNotNull($data, "Tidak ada data supplier dan category di database test");

        // Panggil method model langsung
        $results = SupplierMaterial::getSupplierMaterialByCategory(
            $data->category_id,
            $data->supplier_id
        );

        $this->assertNotEmpty(
            $results,
            "Data tidak ditemukan untuk supplier_id={$data->supplier_id} dan category_id={$data->category_id}"
        );

        $first = $results->first();

        // Pastikan field penting tidak null
        $this->assertNotNull($first->item_id ?? null, 'Item ID tidak boleh null.');
        $this->assertNotNull($first->sku ?? null, 'SKU tidak boleh null.');
        $this->assertNotNull($first->item_name ?? null, 'Item name tidak boleh null.');
        $this->assertNotNull($first->product_id ?? null, 'Product ID tidak boleh null.');
        $this->assertNotNull($first->product_name ?? null, 'Product name tidak boleh null.');
        $this->assertNotNull($first->category_id ?? null, 'Category ID tidak boleh null.');
        $this->assertNotNull($first->category_name ?? null, 'Category name tidak boleh null.');
        $this->assertNotNull($first->supplier_id ?? null, 'Supplier ID tidak boleh null.');
    }

    /** @test */
    public function it_returns_empty_for_non_existing_supplier()
    {
        $results = SupplierMaterial::getSupplierMaterialByCategory(1, 'SUPXXX');
        $this->assertEmpty($results, "Seharusnya tidak ada data untuk supplier_id=SUPXXX");
    }

    /** @test */
    public function it_returns_empty_for_invalid_category()
    {
        $results = SupplierMaterial::getSupplierMaterialByCategory(999999, 'SUP001');
        $this->assertEmpty($results, "Seharusnya tidak ada data untuk category_id tidak valid");
    }
}
