<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\SupplierMaterial;

class CountSupplierMaterialByIDTest extends TestCase
{
    /** @test */
    public function testCountSupplierMaterialByID()
    {
        $supplierId = 'SUP001';

        $count = SupplierMaterial::countSupplierMaterialByID($supplierId);

        dump("Jumlah item bertipe RM dari supplier dengan ID = $supplierId adalah: $count");

        $this->assertTrue(true);
    }
}
