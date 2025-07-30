<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\SupplierPic;

class CountSupplierPICFeatureTest extends TestCase
{
    /** @test */
    public function it_counts_all_pic_for_supplier()
    {
        $supplierID = 'SUP003';

        $total = SupplierPic::countSupplierPIC($supplierID);
        $active = SupplierPic::countActivePIC($supplierID);
        $nonActive = SupplierPic::countNonActivePIC($supplierID);

        // Debug output untuk memastikan
        dump("Total: $total | Aktif: $active | Nonaktif: $nonActive");

        // Tes semua bukan null dan sesuai tipe
        $this->assertIsInt($total);
        $this->assertIsInt($active);
        $this->assertIsInt($nonActive);

        // Total harus sama dengan aktif + nonaktif
        $this->assertEquals($total, $active + $nonActive);
    }
}