<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\SupplierMaterial;

class CountSupplierMaterialTest extends TestCase
{
    public function test_count_supplier_material_by_category()
    {
        $category = 'dolor';       // Sesuaikan kata kunci dari product_name
        $supplierId = 'SUP013';

        $count = SupplierMaterial::countSupplierMaterialByCategory($category, $supplierId);

        $this->assertGreaterThan(0, $count);
        $this->assertIsInt($count);
    }
}
