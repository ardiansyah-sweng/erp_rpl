<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\SupplierMaterial;
use App\Models\Category;

class CountSupplierMaterialTest extends TestCase
{
            public function test_count_supplier_material_by_category()
    {
        $categoryId = 7; // Misal ID dari kategori 'Calendar'
        $supplierId = 'SUP013';

        $count = \App\Models\SupplierMaterial::countSupplierMaterialByCategory($categoryId, $supplierId);

        $this->assertGreaterThanOrEqual(0, $count);
        $this->assertIsInt($count);
    }

}