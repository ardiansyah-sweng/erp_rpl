<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\SupplierMaterial;
use Illuminate\Support\Facades\DB;

class SupplierMaterialTest extends TestCase
{
    public function test_get_supplier_material_list()
    {
        $response = $this->get('/supplier/material');
        $response->assertStatus(200);
        $response->assertViewIs('supplier.material.list');
        $response->assertViewHas('materials');
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
        $response->assertViewHas('material');
    }

    public function test_get_supplier_material_by_category()
    {
        // --- 1. MEMBERSIHKAN DATA DUMMY AGAR TIDAK DUPLIKAT ---
        DB::table('supplier_product')->where('supplier_id', 'T1')->delete();
        DB::table('item')->where('sku', 'S1')->delete();
        DB::table('products')->where('product_id', 'P1')->delete();

        // --- 2. INPUT DATA DUMMY DENGAN FIELD LENGKAP ---
        $categoryId = DB::table('categories')->insertGetId([
            'category' => 'Kategori Tes'
        ]);

        DB::table('products')->insert([
            'product_id' => 'P1',
            'product_name' => 'Tes Produk',
            'product_category' => $categoryId,
            'product_type' => 1,
            'product_description' => 'Deskripsi Tes'
        ]);

        DB::table('item')->insert([
            'sku' => 'S1',
            'product_id' => 'P1',
            'item_name' => 'Tes Item',
            'measurement_unit' => 1
        ]);

        DB::table('supplier_product')->insert([
            'supplier_id' => 'T1',
            'product_id' => 'S1',
            'base_price' => 10000,
            'company_name' => 'PT Tes Maju',
            'product_name' => 'Tes Produk' // MENGATASI ERROR: Field 'product_name' doesn't have a default value
        ]);

        // --- 3. VERIFIKASI ---
        $data = DB::table('supplier_product as sp')
            ->join('item as i', 'i.sku', '=', 'sp.product_id')
            ->join('products as p', 'p.product_id', '=', 'i.product_id')
            ->join('categories as c', 'c.id', '=', 'p.product_category')
            ->select('sp.supplier_id', 'c.id as category_id')
            ->where('sp.supplier_id', 'T1')
            ->first();

        $this->assertNotNull($data, "Data dummy gagal dibaca");

        $results = SupplierMaterial::getSupplierMaterialByCategory(
            $data->category_id,
            $data->supplier_id
        );

        $this->assertNotEmpty($results, "Hasil pencarian category kosong");
    }

    /** @test */
    public function it_returns_empty_for_non_existing_supplier()
    {
        $results = SupplierMaterial::getSupplierMaterialByCategory(1, 'SUPXXX');
        $this->assertEmpty($results);
    }

    /** @test */
    public function it_returns_empty_for_invalid_category()
    {
        $results = SupplierMaterial::getSupplierMaterialByCategory(999999, 'SUP001');
        $this->assertEmpty($results);
    }
}