<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\SupplierMaterial;

class CountSupplierMaterialTest extends TestCase
{
    use RefreshDatabase;
    public function test_countSupplierMaterialByCategory_does_not_crash()
    {
        if (!Schema::hasTable('supplier_materials')) {
            $this->markTestSkipped('Tabel supplier_materials tidak tersedia di database.');
        }

        $count = SupplierMaterial::countSupplierMaterialByCategory('Logam', 1);
        $this->assertIsInt($count);
    }
}
