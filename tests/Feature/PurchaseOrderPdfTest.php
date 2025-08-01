<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\PurchaseOrder;

class PurchaseOrderPdfTest extends TestCase
{

    public function testGeneratesOrderCountPdfForSupplierByDate()
    {
        $startDate = '2025-07-28';
        $endDate = '2025-07-30';
        $supplierID = 99;

        // Buat 2 data dummy PO untuk supplier 99
        PurchaseOrder::create([
            'po_number' => 'PO-101',
            'branch_id' => 1,
            'status' => 'completed',
            'supplier_id' => $supplierID,
            'order_date' => '2025-07-28',
            'total' => 50000
        ]);

        PurchaseOrder::create([
            'po_number' => 'PO-102',
            'branch_id' => 1,
            'status' => 'completed',
            'supplier_id' => $supplierID,
            'order_date' => '2025-07-30',
            'total' => 80000
        ]);

        $po = new PurchaseOrder();

        $pdfContent = $po->generateOrderCountPDFByDateSupplier($startDate, $endDate, $supplierID);

        $this->assertNotNull($pdfContent);
        $this->assertIsString($pdfContent);
        $this->assertStringContainsString('%PDF', $pdfContent);
    }
}
