<?php

namespace Tests\Feature;
use App\Models\PurchaseOrder;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PurchaseOrderbySupplierIDTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $supplierId = "SUP001"; // ganti dengan supplier_id yang ada di database Anda

        // Jalankan fungsi
        $results = PurchaseOrder::getPurchaseOrderBySupplierId($supplierId);

        // Cek apakah hasilnya collection dan tidak error
        $this->assertIsIterable($results);

        // Jika mau pastikan ada data
        $this->assertTrue($results->count() > 0, "Purchase order untuk supplier_id {$supplierId} tidak ditemukan.");
    }
}
