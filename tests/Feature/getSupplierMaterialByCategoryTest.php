<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\SupplierMaterial;

class getSupplierMaterialByCategoryTest extends TestCase
{
    /** @test */
    public function getSupplierMaterialByCategory_returns_valid_data()
    {
        $results = SupplierMaterial::getSupplierMaterialByCategory();

        // Pastikan hasil tidak null dan berupa iterable
        $this->assertNotNull($results, 'Hasil join tidak boleh null');
        $this->assertIsIterable($results, 'Hasil harus bisa diiterasi');

        // Jika hasil tidak kosong, cek struktur datanya
         if (count($results) > 0) {
            $first = $results[0];

            $this->assertTrue(property_exists($first, 'supplier_id'));
            $this->assertTrue(property_exists($first, 'product_id'));
            $this->assertTrue(property_exists($first, 'item_name'));
            $this->assertTrue(property_exists($first, 'measurement_unit'));
        }
    }
}
