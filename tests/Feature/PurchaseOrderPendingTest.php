<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\GoodsReceiptNote;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PurchaseOrderPendingTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_pending_delivery_quantity_is_accurate()
    {
        // Gunakan nomor yang pendek saja supaya tidak melebihi kapasitas kolom DB
        $poNumber = 'P01'; 

        // 1. Simulasi Detail PO: Pesan 10 item Produk A
        PurchaseOrderDetail::create([
            'po_number' => $poNumber,
            'product_id' => 'A1', // Dipendekkan juga untuk jaga-jaga
            'quantity' => 10,
            'amount' => 5000
        ]);

// 2. Simulasi Penerimaan (GRN): Sudah dikirim 4 item
        \App\Models\GoodsReceiptNote::create([
            'po_number' => $poNumber,
            'product_id' => 'A1',
            'delivered_quantity' => 4,
            'delivery_date' => now() // Tambahkan baris ini untuk mengisi tanggal hari ini
        ]);

        // 3. Panggil fungsi yang mau kita tes
        $result = PurchaseOrder::getPendingDeliveryQuantity($poNumber);

        // 4. Cek hasil (10 - 4 = 6)
        $this->assertCount(1, $result);
        $this->assertEquals(6, $result[0]['pending_qty']);
    }
}