<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use App\Models\SupplierMaterial; 

class CountSupplierMaterialTest extends TestCase
{
    public function test_count_supplier_material_by_category_does_not_crash()
    {
        $count = SupplierMaterial::countSupplierMaterialByCategory('Logam', 1);

        $this->assertIsInt($count);
    }
}
