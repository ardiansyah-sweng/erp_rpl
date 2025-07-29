<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\SupplierMaterial;

class SupplierMaterialJoinTest extends TestCase
{
    /** @test */
    public function test_getJoinedSupplierItemData_returns_valid_data()
    {
        $results = SupplierMaterial::getJoinedSupplierItemData();

        // Pastikan hasil tidak null dan berupa iterable
        $this->assertNotNull($results, 'Hasil join tidak boleh null');
        $this->assertIsIterable($results, 'Hasil harus bisa diiterasi');

        // Jika hasil tidak kosong, cek struktur datanya
        if (count($results) > 0) {
            $first = $results[0];
            $this->assertObjectHasAttribute('supplier_id', $first);
            $this->assertObjectHasAttribute('product_id', $first);
            $this->assertObjectHasAttribute('item_name', $first);
            $this->assertObjectHasAttribute('measurement_unit', $first);
        }
    }
}
