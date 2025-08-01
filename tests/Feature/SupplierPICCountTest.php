<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\SupplierPIC;

class SupplierPICCountTest extends TestCase
{
    /** @test */
    public function it_can_call_count_supplier_pic_method()
    {
        $supplierId = 'SUP001';

        // Panggil method tanpa peduli data tersedia atau tidak
        $totalAll = SupplierPIC::countSupplierPIC($supplierId);
        $totalActive = SupplierPIC::countSupplierPIC($supplierId, true);
        $totalInactive = SupplierPIC::countSupplierPIC($supplierId, false);

        dump("Total: {$totalAll}, Aktif: {$totalActive}, Non-aktif: {$totalInactive}");

        $this->assertIsInt($totalAll);
        $this->assertIsInt($totalActive);
        $this->assertIsInt($totalInactive);
    }
}