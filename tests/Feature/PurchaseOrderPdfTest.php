<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Models\PurchaseOrder;

class PurchaseOrderPdfTest extends TestCase
{

    public function test_counts_orders_by_supplier_and_date()
    {
        DB::table('purchase_order')->truncate();

        PurchaseOrder::insert([
            [
                'po_number' => 'PO001',
                'branch_id' => 1,
                'status' => 'completed',
                'supplier_id' => 1,
                'order_date' => '2025-07-28',
                'total' => 100000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'po_number' => 'PO002',
                'branch_id' => 1,
                'status' => 'completed',
                'supplier_id' => 1,
                'order_date' => '2025-07-28',
                'total' => 200000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'po_number' => 'PO003',
                'branch_id' => 1,
                'status' => 'completed',
                'supplier_id' => 2,
                'order_date' => '2025-07-29',
                'total' => 150000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Revisi: 3 parameter (startDate, endDate, supplierId)
        $result = PurchaseOrder::countOrdersByDateSupplier('2025-07-28', '2025-07-28', '1');

        $this->assertEquals(2, $result);
    }

}