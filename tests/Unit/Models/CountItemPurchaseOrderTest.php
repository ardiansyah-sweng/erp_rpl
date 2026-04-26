<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;

class CountItemPurchaseOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // ensure migrations are run for the detail table
        $this->artisan('migrate');
    }

    /**
     * Test that countItem returns correct count for a PO with multiple details
     */
    public function test_countItem_returns_correct_count_for_multiple_details()
    {
        // Arrange
        PurchaseOrderDetail::factory()->create(['po_number' => 'PO100', 'product_id' => 'PROD-1']);
        PurchaseOrderDetail::factory()->create(['po_number' => 'PO100', 'product_id' => 'PROD-2']);
        PurchaseOrderDetail::factory()->create(['po_number' => 'PO100', 'product_id' => 'PROD-3']);

        // Act
        $count = PurchaseOrder::countItem('PO100');

        // Assert
        $this->assertEquals(3, $count);
    }

    /**
     * Test that countItem returns zero when there are no details for the PO
     */
    public function test_countItem_returns_zero_when_no_details_exist()
    {
        // Arrange - create details for another PO
        PurchaseOrderDetail::factory()->create(['po_number' => 'PO200', 'product_id' => 'PROD-1']);

        // Act
        $count = PurchaseOrder::countItem('PO_NOT_EXIST');

        // Assert
        $this->assertEquals(0, $count);
    }

    /**
     * Test that countItem counts only items belonging to the specified PO
     */
    public function test_countItem_counts_only_specified_po()
    {
        // Arrange: create details for two different POs
        PurchaseOrderDetail::factory()->create(['po_number' => 'PO300', 'product_id' => 'PROD-1']);
        PurchaseOrderDetail::factory()->create(['po_number' => 'PO300', 'product_id' => 'PROD-2']);
        PurchaseOrderDetail::factory()->create(['po_number' => 'PO400', 'product_id' => 'PROD-3']);

        // Act
        $countPO300 = PurchaseOrder::countItem('PO300');
        $countPO400 = PurchaseOrder::countItem('PO400');

        // Assert
        $this->assertEquals(2, $countPO300);
        $this->assertEquals(1, $countPO400);
    }
}
