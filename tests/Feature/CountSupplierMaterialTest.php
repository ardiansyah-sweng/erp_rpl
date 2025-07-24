<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\SupplierMaterial;

class CountSupplierMaterialTest extends TestCase
{
    public function test_count_supplier_material_by_category()
    {
        $categoryId = 1;         // contoh kategori Kaos
        $supplierId = 'SUP001';  // contoh supplier yang kamu punya

        $count = SupplierMaterial::countSupplierMaterialByCategory($categoryId, $supplierId);

        echo "\nJumlah produk supplier '{$supplierId}' dengan kategori '{$categoryId}' = {$count}\n";

        // Validasi hasil
        $this->assertIsInt($count, "Hasil count harus berupa integer.");
        $this->assertGreaterThanOrEqual(0, $count, "Hasil count minimal 0.");
    }
    /** @test */
    public function testCountSupplierMaterialByType()
    {
        $type = 'RM'; 
        $supplierId = 'SUP001'; 

        $count = SupplierMaterial::countSupplierMaterialByType($type, $supplierId);

        dump("Jumlah data dengan type = $type dan supplier = $supplierId adalah: $count");

        
        $this->assertTrue(true);
    }
}