<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\SupplierMaterial;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SupplierMaterialCategoryNewTest extends TestCase
{
    /** @test */
    public function get_supplier_material_by_category()
    {
        // kategori dan supplier_id yang valid dari database kamu
        $category = 'KAOS';
        $supplier = 'SUP001';

        $results = SupplierMaterial::getSupplierMaterialByCategory($category, $supplier);

        // Tampilkan hasil untuk dicek manual
        dump($results);

        // Assertion dasar (misalnya harus berupa koleksi dan tidak kosong jika data ada)
        $this->assertIsIterable($results);
    }
}
