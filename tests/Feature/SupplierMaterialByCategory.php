<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Models\SupplierMaterial;

class SupplierMaterialByCategory extends TestCase
{
    public function test_get_supplier_material_by_category_with_existing_data()
    {
        $categoryId = 30;
        $supplierId = 'SUP005';

        $result = SupplierMaterial::getSupplierMaterialByCategory($categoryId, $supplierId);

        $this->assertNotEmpty($result, 'Data supplier material tidak ditemukan.');

        // Periksa struktur data yang dikembalikan
        foreach ($result as $item) {
            $this->assertEquals($categoryId, $item->product_category, 'Kategori produk tidak sesuai.');
            $this->assertEquals($supplierId, $item->supplier_id, 'Supplier ID tidak sesuai.');
            $this->assertTrue(property_exists($item, 'product_name'), 'Tidak ada atribut product_name.');
        }
    }
}
