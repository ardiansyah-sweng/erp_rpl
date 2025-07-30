<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\PurchaseOrder;


class PurchaseOrderPdfTest extends TestCase
{


    /** @test */
    public function it_generates_pdf_for_supplier_order_report()
    {
        $startDate = '2025-07-28';
        $endDate = '2025-07-30';
        $supplierID = 1;

        // Siapkan data dummy
        PurchaseOrder::create([
            'po_number' => 'PO-002',
            'branch_id' => 1,
            'status' => 'completed',
            'supplier_id' => $supplierID,
            'order_date' => '2025-07-29',
            'total' => 100000
        ]);

        $purchaseOrder = new PurchaseOrder();

        // Jalankan fungsi generatePDF
        $response = $purchaseOrder->generatePDFByDateSupplier($startDate, $endDate, $supplierID);

        // Assertion
        $this->assertNotNull($response);
        $this->assertIsString($response);
        $this->assertStringContainsString('%PDF', $response); // header PDF
    }
}
