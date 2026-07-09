<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\PurchaseOrder;
use App\Helpers\EncryptionHelper;

class EditPurchaseOrderTest extends TestCase
{
    public function test_halaman_edit_purchase_order_berhasil_tampil()
    {
        // Siapkan data dummy
        $po = PurchaseOrder::create([
            'po_number'   => 'PO9999',
            'supplier_id' => 'SUP001',
            'total'       => 150000,
            'branch_id'   => 1,
            'order_date'  => '2026-06-30',
            'status'      => 'Draft'
        ]);

        // Enkripsi dan akses rute
        $encryptedId = EncryptionHelper::encrypt($po->po_number);
        $response = $this->get('/purchase_orders/edit/' . $encryptedId);

        // Pengecekan
        $response->assertStatus(200);
        $response->assertViewIs('purchase_orders.edit');
        
        // Bersihkan data
        $po->delete();
    }

    public function test_update_status_purchase_order_berhasil()
    {
        // Siapkan data dummy
        $po = PurchaseOrder::create([
            'po_number'   => 'PO8888',
            'supplier_id' => 'SUP001',
            'total'       => 150000,
            'branch_id'   => 1,
            'order_date'  => '2026-06-30',
            'status'      => 'Draft'
        ]);

        // Tembak rute update
        $response = $this->put('/purchase_orders/update/' . $po->po_number, [
            'status' => 'Approved'
        ]);

        // Pengecekan
        $response->assertRedirect(route('purchase.orders'));
        $this->assertDatabaseHas('purchase_order', [
            'po_number' => 'PO8888',
            'status'    => 'Approved'
        ]);

        // Bersihkan data
        $po->delete();
    }
}