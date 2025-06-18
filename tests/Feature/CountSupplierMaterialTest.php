<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use App\Models\SupplierMaterialModel;

class CountSupplierMaterialTest extends TestCase
{
    public function test_countSupplierMaterialByCategory_does_not_crash()
    {
        if (!Schema::hasTable('supplier_materials')) {
            $this->markTestSkipped('Tabel supplier_materials tidak tersedia di database.');
        }

        $count = SupplierMaterialModel::countSupplierMaterialByCategory('Logam', 1);
        $this->assertIsInt($count);
    }
}
