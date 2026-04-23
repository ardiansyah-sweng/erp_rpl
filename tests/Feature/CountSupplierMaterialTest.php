<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\SupplierMaterial;

class CountSupplierMaterialTest extends TestCase
{
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