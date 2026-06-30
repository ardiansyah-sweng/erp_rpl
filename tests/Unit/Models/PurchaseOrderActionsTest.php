<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Enums\POStatus;

class PurchaseOrderActionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    /**
     * Test deleting a purchase order and its related details.
     */
    public function test_delete_purchase_order_removes_both_header_and_details()
    {
        // Create a PO header
        PurchaseOrder::create([
            'po_number' => 'PO123',
            'supplier_id' => 'SUP123',
            'branch_id' => 1,
            'total' => 150000,
            'order_date' => now()->toDateString(),
            'status' => POStatus::Draft->value,
        ]);

        // Create related details
        PurchaseOrderDetail::factory()->create([
            'po_number' => 'PO123',
            'product_id' => 'PROD-A',
        ]);
        PurchaseOrderDetail::factory()->create([
            'po_number' => 'PO123',
            'product_id' => 'PROD-B',
        ]);

        // Make sure they exist in the DB
        $this->assertDatabaseHas('purchase_order', ['po_number' => 'PO123']);
        $this->assertDatabaseHas('purchase_order_detail', ['po_number' => 'PO123', 'product_id' => 'PROD-A']);
        $this->assertDatabaseHas('purchase_order_detail', ['po_number' => 'PO123', 'product_id' => 'PROD-B']);

        // Execute delete
        $result = PurchaseOrder::deletePurchaseOrder('PO123');

        $this->assertTrue($result > 0);

        // Verify they are gone
        $this->assertDatabaseMissing('purchase_order', ['po_number' => 'PO123']);
        $this->assertDatabaseMissing('purchase_order_detail', ['po_number' => 'PO123']);
    }

    /**
     * Test updating the status of a purchase order.
     */
    public function test_update_status_changes_po_status_correctly()
    {
        // Create a PO
        PurchaseOrder::create([
            'po_number' => 'PO456',
            'supplier_id' => 'SUP123',
            'branch_id' => 1,
            'total' => 50000,
            'order_date' => now()->toDateString(),
            'status' => POStatus::Draft->value,
        ]);

        // Execute status update
        $updatedPO = PurchaseOrder::updateStatus('PO456', POStatus::Approved->value);

        $this->assertNotNull($updatedPO);
        $this->assertEquals(POStatus::Approved->value, $updatedPO->status);

        // Verify database has updated status
        $this->assertDatabaseHas('purchase_order', [
            'po_number' => 'PO456',
            'status' => POStatus::Approved->value,
        ]);
    }
}
