<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Models\SupplierMaterial;

class CountSupplierMaterialTest extends TestCase
{
    public function test_count_supplier_material_by_category()
    {
        // Pastikan kategori, produk, dan supplier_product sudah ada di database nyata
        $categoryId = 7; // ID kategori yang memang sudah ada di database
        $supplierId = 'SUP013'; // ID supplier yang juga sudah ada

        // Jalankan fungsi yang diuji
        $count = SupplierMaterial::countSupplierMaterialByCategory($categoryId, $supplierId);

        // Assertion
        $this->assertIsInt($count);
        $this->assertGreaterThanOrEqual(0, $count);
    }
}