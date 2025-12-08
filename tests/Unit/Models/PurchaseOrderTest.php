<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\PurchaseOrder;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PurchaseOrderTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * Test that countPurchaseOrder() returns the correct count when multiple PurchaseOrder records exist in the database.
     * This test creates 3 sample records and verifies the method counts them accurately.
     */
    public function testCountPurchaseOrderWithMultipleRecords()
    {
        // Create sample PurchaseOrder records
        PurchaseOrder::create([
            'po_number' => 'PO001',
            'branch_id' => 1,
            'supplier_id' => 1,
            'order_date' => now(),
            'total' => 1000,
        ]);

        PurchaseOrder::create([
            'po_number' => 'PO002',
            'branch_id' => 1,
            'supplier_id' => 2,
            'order_date' => now(),
            'total' => 2000,
        ]);

        PurchaseOrder::create([
            'po_number' => 'PO003',
            'branch_id' => 2,
            'supplier_id' => 1,
            'order_date' => now(),
            'total' => 1500,
        ]);

        // Call the method and assert the count
        $count = PurchaseOrder::countPurchaseOrder();
        $this->assertEquals(3, $count);
    }

    /**
     * @test
     * Test that countPurchaseOrder() returns 0 when no PurchaseOrder records exist in the database.
     * This ensures the method handles empty tables correctly.
     */
    public function testCountPurchaseOrderWithZeroRecords()
    {
        // No records created
        $count = PurchaseOrder::countPurchaseOrder();
        $this->assertEquals(0, $count);
    }

    /**
     * @test
     * Test that countPurchaseOrder() returns the correct count when only one PurchaseOrder record exists.
     * This test creates a single record and verifies the method counts it as 1.
     */
    public function testCountPurchaseOrderWithSingleRecord()
    {
        // Create one sample PurchaseOrder record
        PurchaseOrder::create([
            'po_number' => 'PO001',
            'branch_id' => 1,
            'supplier_id' => 1,
            'order_date' => now(),
            'total' => 1000,
        ]);

        // Call the method and assert the count
        $count = PurchaseOrder::countPurchaseOrder();
        $this->assertEquals(1, $count);
    }
}
