<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\PurchaseOrder;

class PrintPurchaseOrderTest extends TestCase
{
    public function test_print_purchase_order_route_exists()
    {
        $poNumber = 'PO' . rand(1000, 9999);
        $supplierId = 'SUP' . rand(100, 999);

        $purchaseOrder = PurchaseOrder::create([
            'po_number' => $poNumber,
            'supplier_id' => $supplierId,
            'total' => 500000,
            'branch_id' => 1,
            'order_date' => now(),
            'status' => 'pending',
        ]);

        $response = $this->get("/purchase-orders/print-pdf/{$purchaseOrder->po_number}");

        $response->assertStatus(200);
    }

    public function test_print_purchase_order_with_invalid_id()
    {
        $poNumber = 'PO' . rand(1000, 9999);
        $supplierId = 'SUP' . rand(100, 999);

        PurchaseOrder::create([
            'po_number' => $poNumber,
            'supplier_id' => $supplierId,
            'total' => 750000,
            'branch_id' => 1,
            'order_date' => now(),
            'status' => 'completed',
        ]);

        $response = $this->get('/purchase-orders/print-pdf/XXXXX');

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Purchase Order tidak ditemukan.');
    }

    public function test_print_purchase_order_controller_method_exists()
    {
        $poNumber = 'PO' . rand(1000, 9999);
        $supplierId = 'SUP' . rand(100, 999);

        PurchaseOrder::create([
            'po_number' => $poNumber,
            'supplier_id' => $supplierId,
            'total' => 1000000,
            'branch_id' => 1,
            'order_date' => now(),
            'status' => 'draft',
        ]);

        $this->assertTrue(method_exists(\App\Http\Controllers\PurchaseOrderController::class, 'printPurchaseOrderToPDF'));
    }
}




