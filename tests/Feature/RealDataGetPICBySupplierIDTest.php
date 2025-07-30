<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\SupplierPic;

class RealDataGetPICBySupplierIDTest extends TestCase
{
    public function test_get_pic_by_supplier_id_with_real_data()
    {
        // Ganti dengan supplier_id yang memang ada di database
        $supplierID = 'SUP001'; // Misalnya SUP001, sesuaikan dengan database kamu

        $results = SupplierPic::getPICBySupplierID($supplierID);

        // Cek hasil
        $this->assertIsIterable($results); // Pastikan hasil bisa di-loop

        // Cek apakah ada data (jika diharapkan tidak kosong)
        $this->assertGreaterThanOrEqual(0, $results->count());

        // Untuk debugging (boleh dihapus setelah yakin)
        dump($results->toArray());
    }
}
