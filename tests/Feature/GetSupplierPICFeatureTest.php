<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\SupplierPic;

class GetSupplierPICFeatureTest extends TestCase
{
    /** @test */
    public function it_can_fetch_all_pic_by_supplier_id()
    {
        // Gunakan data asli dari tabel, misal supplier_id = 'SUP001'
        $supplierID = 'SUP001';

        // Panggil fungsi yang sudah kamu buat di model
        $result = SupplierPic::getSupplierPIC($supplierID);

        // Pastikan hasilnya bukan null
        $this->assertNotNull($result);

        // Pastikan hasilnya collection dan tidak kosong
        $this->assertIsIterable($result);
        $this->assertNotEmpty($result);

        // Pastikan semua data yang diambil memiliki supplier_id yang sama
        foreach ($result as $pic) {
            $this->assertEquals($supplierID, $pic->supplier_id);
        }
    }
}
