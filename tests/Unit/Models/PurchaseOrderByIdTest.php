<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\PurchaseOrder;

class PurchaseOrderByIdTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_purchase_order_by_id()
    {
        // 1. ARRANGE
        $poNumber = 'PO001';
        
        PurchaseOrder::create([
            'po_number'   => $poNumber,
            'supplier_id' => 'SUP001',
            'total'       => 150000,
            'branch_id'   => 1,
            'order_date'  => now(),
            'status'      => 'Pending',
        ]);

        // 2. ACT
        $result = PurchaseOrder::getPurchaseOrderByID($poNumber);

        // 3. ASSERT
        $this->assertNotNull($result, 'Data harus ditemukan.');
        $this->assertEquals($poNumber, $result->po_number);
    }

    /** @test */
    public function it_returns_null_if_po_number_not_found()
    {
        // 1. ARRANGE
        PurchaseOrder::create([
            'po_number'   => 'PO001',
            'supplier_id' => 'SUP001',
            'total'       => 0,
            'branch_id'   => 1,
            'order_date'  => now(),
            'status'      => 'Pending',
        ]);

        // 2. ACT
        $result = PurchaseOrder::getPurchaseOrderByID('PO999');

        // 3. ASSERT
        $this->assertNull($result, 'Harus return null jika data tidak ditemukan.');
    }
}