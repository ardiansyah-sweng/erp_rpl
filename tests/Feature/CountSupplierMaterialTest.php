<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\SupplierMaterial;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CountSupplierMaterialTest extends TestCase
{
    // Jika mau DB refresh otomatis:
    // use RefreshDatabase;

    public function test_count_supplier_material_by_category()
    {
    
        $categoryId = 1;         // contoh kategori Kaos
        $supplierId = 'SUP001';  // contoh supplier yang kamu punya

        $count = SupplierMaterial::countSupplierMaterialByCategory($categoryId, $supplierId);

        echo "\nJumlah produk supplier '{$supplierId}' dengan kategori '{$categoryId}'= {$count}\n";

        // Validasi hasil
        $this->assertIsInt($count, "Hasil count harus berupa integer.");
        $this->assertGreaterThanOrEqual(0, $count, "Hasil count minimal 0.");

    }
}
