<?php

namespace Tests\Unit\Model;

use Tests\TestCase;
use App\Models\PurchaseOrder;
use App\Enums\POStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CountStatusPOTest extends TestCase
{
    use RefreshDatabase;

    public function test_count_status_po_with_no_purchase_orders()
    {
        $result = PurchaseOrder::countStatusPO();
        $this->assertEmpty($result);
    }

    public function test_count_status_po_with_single_purchase_order()
    {
        PurchaseOrder::create([
            'po_number' => 'PO001',
            'supplier_id' => 1,
            'branch_id' => 1,
            'order_date' => now(),
            'total' => 1000,
            'status' => POStatus::Draft->value,
        ]);

        $result = PurchaseOrder::countStatusPO();
        $draft = collect($result)->firstWhere('status', POStatus::Draft->value);

        $this->assertEquals(1, $draft->total);
    }

    public function test_count_status_po_with_multiple_purchase_orders_same_status()
    {
        PurchaseOrder::create([
            'po_number' => 'PO001',
            'supplier_id' => 1,
            'branch_id' => 1,
            'order_date' => now(),
            'total' => 1000,
            'status' => POStatus::Approved->value,
        ]);

        PurchaseOrder::create([
            'po_number' => 'PO002',
            'supplier_id' => 1,
            'branch_id' => 1,
            'order_date' => now(),
            'total' => 2000,
            'status' => POStatus::Approved->value,
        ]);

        $result = PurchaseOrder::countStatusPO();
        $approved = collect($result)->firstWhere('status', POStatus::Approved->value);

        $this->assertEquals(2, $approved->total);
    }

    public function test_count_status_po_with_multiple_purchase_orders_different_statuses()
    {
        PurchaseOrder::create([
            'po_number' => 'PO001',
            'supplier_id' => 1,
            'branch_id' => 1,
            'order_date' => now(),
            'total' => 1000,
            'status' => POStatus::Draft->value,
        ]);

        PurchaseOrder::create([
            'po_number' => 'PO002',
            'supplier_id' => 1,
            'branch_id' => 1,
            'order_date' => now(),
            'total' => 2000,
            'status' => POStatus::Submitted->value,
        ]);

        PurchaseOrder::create([
            'po_number' => 'PO003',
            'supplier_id' => 1,
            'branch_id' => 1,
            'order_date' => now(),
            'total' => 3000,
            'status' => POStatus::Approved->value,
        ]);

        PurchaseOrder::create([
            'po_number' => 'PO004',
            'supplier_id' => 1,
            'branch_id' => 1,
            'order_date' => now(),
            'total' => 4000,
            'status' => POStatus::Approved->value,
        ]);

        $result = PurchaseOrder::countStatusPO();

        $this->assertEquals(1, collect($result)->firstWhere('status', POStatus::Draft->value)->total);
        $this->assertEquals(1, collect($result)->firstWhere('status', POStatus::Submitted->value)->total);
        $this->assertEquals(2, collect($result)->firstWhere('status', POStatus::Approved->value)->total);
    }

    public function test_count_status_po_with_all_statuses()
    {
        $statuses = [
            POStatus::Draft,
            POStatus::Submitted,
            POStatus::InReview,
            POStatus::Revised,
            POStatus::Approved,
            POStatus::Rejected,
            POStatus::Cancelled,
            POStatus::Closed,
            POStatus::PL,
            POStatus::FD,
        ];

        foreach ($statuses as $index => $status) {
            PurchaseOrder::create([
                'po_number' => 'PO' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'supplier_id' => 1,
                'branch_id' => 1,
                'order_date' => now(),
                'total' => 1000 * ($index + 1),
                'status' => $status->value,
            ]);
        }

        $result = PurchaseOrder::countStatusPO();

        foreach ($statuses as $status) {
            $item = collect($result)->firstWhere('status', $status->value);
            $this->assertEquals(1, $item->total);
        }
    }

    // Additional Test Case 1: Count status PO with mixed suppliers and statuses
    public function test_countStatusPO_with_mixed_suppliers_and_statuses()
    {
        // Arrange: Create POs with different suppliers and statuses
        PurchaseOrder::create([
            'po_number' => 'PO101',
            'supplier_id' => 1,
            'branch_id' => 1,
            'order_date' => now(),
            'total' => 1000,
            'status' => POStatus::Draft->value,
        ]);

        PurchaseOrder::create([
            'po_number' => 'PO102',
            'supplier_id' => 2,
            'branch_id' => 1,
            'order_date' => now(),
            'total' => 2000,
            'status' => POStatus::Draft->value,
        ]);

        PurchaseOrder::create([
            'po_number' => 'PO103',
            'supplier_id' => 1,
            'branch_id' => 1,
            'order_date' => now(),
            'total' => 3000,
            'status' => POStatus::Approved->value,
        ]);

        // Act
        $result = PurchaseOrder::countStatusPO();

        // Assert
        $this->assertEquals(2, $result[POStatus::Draft->value]->total);
        $this->assertEquals(1, $result[POStatus::Approved->value]->total);
    }

    // Additional Test Case 2: Count status PO with only one status present
    public function test_countStatusPO_with_only_one_status_present()
    {
        // Arrange: Create POs with only one status
        PurchaseOrder::create([
            'po_number' => 'PO201',
            'supplier_id' => 1,
            'branch_id' => 1,
            'order_date' => now(),
            'total' => 1000,
            'status' => POStatus::Submitted->value,
        ]);

        PurchaseOrder::create([
            'po_number' => 'PO202',
            'supplier_id' => 1,
            'branch_id' => 1,
            'order_date' => now(),
            'total' => 2000,
            'status' => POStatus::Submitted->value,
        ]);

        PurchaseOrder::create([
            'po_number' => 'PO203',
            'supplier_id' => 2,
            'branch_id' => 1,
            'order_date' => now(),
            'total' => 3000,
            'status' => POStatus::Submitted->value,
        ]);

        // Act
        $result = PurchaseOrder::countStatusPO();

        // Assert
        $this->assertCount(1, $result);
        $this->assertEquals(3, $result[POStatus::Submitted->value]->total);
    }
}
