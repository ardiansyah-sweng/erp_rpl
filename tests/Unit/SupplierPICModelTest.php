<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\SupplierPICModel;

class SupplierPICModelTest extends TestCase
{
    /** @test */
    public function it_can_count_all_pic_for_a_given_supplier_id()
    {
        $supplierID = 'SUP001'; // Pastikan ID ini ada di database

        $result = SupplierPICModel::countSupplierPIC($supplierID);

        $this->assertIsInt($result);
        $this->assertGreaterThanOrEqual(0, $result);
    }
}
