<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Enums\POStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class PurchaseOrderTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup database tables
        $this->artisan('migrate');
    }

    /**
     * Test getPurchaseOrderByKeywords() without search parameter
     */
    public function test_get_purchase_order_by_keywords_returns_all_purchase_orders()
    {
        // Arrange - Create test supplier
        $supplier = Supplier::create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Supplier Alpha',
            'address' => 'Jl. Supplier No. 1',
            'phone_number' => '021-1111111',
            'bank_account' => '1234567890',
        ]);

        // Create test purchase orders
        $po1 = PurchaseOrder::create([
            'po_number' => 'PO0001',
            'supplier_id' => 'SUP001',
            'branch_id' => 1,
            'order_date' => '2024-01-15',
            'total' => 1000000,
            'status' => POStatus::Draft->value,
        ]);

        $po2 = PurchaseOrder::create([
            'po_number' => 'PO0002',
            'supplier_id' => 'SUP001',
            'branch_id' => 1,
            'order_date' => '2024-01-16',
            'total' => 2000000,
            'status' => POStatus::Approved->value,
        ]);

        // Act - Call getPurchaseOrderByKeywords without search
        $result = PurchaseOrder::getPurchaseOrderByKeywords();

        // Assert - Should return paginated results with all purchase orders
        $this->assertNotNull($result);
        $this->assertEquals(2, $result->total());
        $this->assertCount(2, $result->items());
        
        // Assert data contains created purchase orders
        $poNumbers = $result->pluck('po_number')->toArray();
        $this->assertContains('PO0001', $poNumbers);
        $this->assertContains('PO0002', $poNumbers);
    }

    /**
     * Test getPurchaseOrderByKeywords() with search parameter - search by po_number
     */
    public function test_get_purchase_order_by_keywords_with_search_by_po_number()
    {
        // Arrange - Create test supplier
        $supplier = Supplier::create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Supplier Alpha',
            'address' => 'Jl. Supplier No. 1',
            'phone_number' => '021-1111111',
            'bank_account' => '1234567890',
        ]);

        // Create test purchase orders
        PurchaseOrder::create([
            'po_number' => 'PO0001',
            'supplier_id' => 'SUP001',
            'branch_id' => 1,
            'order_date' => '2024-01-15',
            'total' => 1000000,
            'status' => POStatus::Draft->value,
        ]);

        PurchaseOrder::create([
            'po_number' => 'PO0002',
            'supplier_id' => 'SUP001',
            'branch_id' => 1,
            'order_date' => '2024-01-16',
            'total' => 2000000,
            'status' => POStatus::Approved->value,
        ]);

        // Act - Search by po_number containing 'PO0001'
        $result = PurchaseOrder::getPurchaseOrderByKeywords('PO0001');

        // Assert - Should return only purchase orders with 'PO0001' in po_number
        $this->assertNotNull($result);
        $this->assertEquals(1, $result->total());
        $this->assertCount(1, $result->items());
        $this->assertStringContainsString('PO0001', $result->first()->po_number);
    }

    /**
     * Test getPurchaseOrderByKeywords() with search parameter - search by status
     */
    public function test_get_purchase_order_by_keywords_with_search_by_status()
    {
        // Arrange - Create test supplier
        $supplier = Supplier::create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Supplier Alpha',
            'address' => 'Jl. Supplier No. 1',
            'phone_number' => '021-1111111',
            'bank_account' => '1234567890',
        ]);

        // Create test purchase orders with different statuses
        PurchaseOrder::create([
            'po_number' => 'PO0001',
            'supplier_id' => 'SUP001',
            'branch_id' => 1,
            'order_date' => '2024-01-15',
            'total' => 1000000,
            'status' => POStatus::Draft->value,
        ]);

        PurchaseOrder::create([
            'po_number' => 'PO0002',
            'supplier_id' => 'SUP001',
            'branch_id' => 1,
            'order_date' => '2024-01-16',
            'total' => 2000000,
            'status' => POStatus::Approved->value,
        ]);

        PurchaseOrder::create([
            'po_number' => 'PO0003',
            'supplier_id' => 'SUP001',
            'branch_id' => 1,
            'order_date' => '2024-01-17',
            'total' => 3000000,
            'status' => POStatus::Approved->value,
        ]);

        // Act - Search by status containing 'Approved'
        $result = PurchaseOrder::getPurchaseOrderByKeywords('Approved');

        // Assert - Should return only purchase orders with 'Approved' status
        $this->assertNotNull($result);
        $this->assertEquals(2, $result->total());
        $this->assertCount(2, $result->items());
        
        foreach ($result->items() as $po) {
            $this->assertStringContainsString('Approved', $po->status);
        }
    }

    /**
     * Test getPurchaseOrderByKeywords() with search parameter - search by supplier company_name
     */
    public function test_get_purchase_order_by_keywords_with_search_by_supplier_name()
    {
        // Arrange - Create test suppliers
        $supplier1 = Supplier::create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Jakarta Supplier',
            'address' => 'Jl. Jakarta No. 1',
            'phone_number' => '021-1111111',
            'bank_account' => '1234567890',
        ]);

        $supplier2 = Supplier::create([
            'supplier_id' => 'SUP002',
            'company_name' => 'PT Bandung Supplier',
            'address' => 'Jl. Bandung No. 2',
            'phone_number' => '022-2222222',
            'bank_account' => '0987654321',
        ]);

        // Create test purchase orders
        PurchaseOrder::create([
            'po_number' => 'PO0001',
            'supplier_id' => 'SUP001',
            'branch_id' => 1,
            'order_date' => '2024-01-15',
            'total' => 1000000,
            'status' => POStatus::Draft->value,
        ]);

        PurchaseOrder::create([
            'po_number' => 'PO0002',
            'supplier_id' => 'SUP002',
            'branch_id' => 1,
            'order_date' => '2024-01-16',
            'total' => 2000000,
            'status' => POStatus::Approved->value,
        ]);

        // Act - Search by supplier company_name containing 'Jakarta'
        $result = PurchaseOrder::getPurchaseOrderByKeywords('Jakarta');

        // Assert - Should return only purchase orders with supplier containing 'Jakarta'
        $this->assertNotNull($result);
        $this->assertEquals(1, $result->total());
        $this->assertCount(1, $result->items());
        $this->assertStringContainsString('Jakarta', $result->first()->supplier->company_name);
    }

    /**
     * Test getPurchaseOrderByKeywords() with search that returns no results
     */
    public function test_get_purchase_order_by_keywords_with_search_no_results()
    {
        // Arrange - Create test supplier and purchase order
        $supplier = Supplier::create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Supplier Alpha',
            'address' => 'Jl. Supplier No. 1',
            'phone_number' => '021-1111111',
            'bank_account' => '1234567890',
        ]);

        PurchaseOrder::create([
            'po_number' => 'PO0001',
            'supplier_id' => 'SUP001',
            'branch_id' => 1,
            'order_date' => '2024-01-15',
            'total' => 1000000,
            'status' => POStatus::Draft->value,
        ]);

        // Act - Search with keyword that doesn't exist
        $result = PurchaseOrder::getPurchaseOrderByKeywords('NonExistentKeyword');

        // Assert - Should return empty paginated result
        $this->assertNotNull($result);
        $this->assertEquals(0, $result->total());
        $this->assertCount(0, $result->items());
    }

    /**
     * Test getPurchaseOrderByKeywords() returns paginated results
     */
    public function test_get_purchase_order_by_keywords_returns_paginated_results()
    {
        // Arrange - Create test supplier
        $supplier = Supplier::create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Supplier Alpha',
            'address' => 'Jl. Supplier No. 1',
            'phone_number' => '021-1111111',
            'bank_account' => '1234567890',
        ]);

        // Create multiple purchase orders
        for ($i = 1; $i <= 15; $i++) {
            PurchaseOrder::create([
                'po_number' => sprintf('PO%04d', $i),
                'supplier_id' => 'SUP001',
                'branch_id' => 1,
                'order_date' => '2024-01-15',
                'total' => 1000000 * $i,
                'status' => $i % 2 === 0 ? POStatus::Approved->value : POStatus::Draft->value,
            ]);
        }

        // Act - Get paginated results
        $result = PurchaseOrder::getPurchaseOrderByKeywords();

        // Assert - Should return paginated results
        $this->assertNotNull($result);
        $this->assertEquals(15, $result->total());
        
        // Assert pagination metadata exists
        $this->assertNotNull($result->currentPage());
        $this->assertNotNull($result->perPage());
        $this->assertNotNull($result->lastPage());
        $this->assertEquals(10, $result->perPage());
    }

    /**
     * Test getPurchaseOrderByKeywords() with empty database
     */
    public function test_get_purchase_order_by_keywords_with_empty_database()
    {
        // Act - Call getPurchaseOrderByKeywords with empty database
        $result = PurchaseOrder::getPurchaseOrderByKeywords();

        // Assert - Should return empty paginated result
        $this->assertNotNull($result);
        $this->assertEquals(0, $result->total());
        $this->assertCount(0, $result->items());
    }

    /**
     * Test getPurchaseOrderByKeywords() ordering - should be ordered by created_at asc
     */
    public function test_get_purchase_order_by_keywords_ordering()
    {
        // Arrange - Create test supplier
        $supplier = Supplier::create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Supplier Alpha',
            'address' => 'Jl. Supplier No. 1',
            'phone_number' => '021-1111111',
            'bank_account' => '1234567890',
        ]);

        // Create purchase orders with different timestamps
        $po1 = PurchaseOrder::create([
            'po_number' => 'PO0001',
            'supplier_id' => 'SUP001',
            'branch_id' => 1,
            'order_date' => '2024-01-15',
            'total' => 1000000,
            'status' => POStatus::Draft->value,
        ]);

        // Small delay to ensure different timestamps
        sleep(1);

        $po2 = PurchaseOrder::create([
            'po_number' => 'PO0002',
            'supplier_id' => 'SUP001',
            'branch_id' => 1,
            'order_date' => '2024-01-16',
            'total' => 2000000,
            'status' => POStatus::Approved->value,
        ]);

        // Act - Get all purchase orders
        $result = PurchaseOrder::getPurchaseOrderByKeywords();

        // Assert - Should be ordered by created_at ascending (oldest first)
        $this->assertNotNull($result);
        $this->assertEquals(2, $result->total());
        $this->assertEquals('PO0001', $result->first()->po_number);
        $this->assertEquals('PO0002', $result->last()->po_number);
    }

    /**
     * Test getPurchaseOrderByKeywords() loads supplier relationship
     */
    public function test_get_purchase_order_by_keywords_loads_supplier_relationship()
    {
        // Arrange - Create test supplier
        $supplier = Supplier::create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Supplier Alpha',
            'address' => 'Jl. Supplier No. 1',
            'phone_number' => '021-1111111',
            'bank_account' => '1234567890',
        ]);

        // Create test purchase order
        PurchaseOrder::create([
            'po_number' => 'PO0001',
            'supplier_id' => 'SUP001',
            'branch_id' => 1,
            'order_date' => '2024-01-15',
            'total' => 1000000,
            'status' => POStatus::Draft->value,
        ]);

        // Act - Get purchase orders
        $result = PurchaseOrder::getPurchaseOrderByKeywords();

        // Assert - Supplier relationship should be loaded
        $this->assertNotNull($result);
        $this->assertEquals(1, $result->total());
        
        $po = $result->first();
        $this->assertNotNull($po->supplier);
        $this->assertEquals('SUP001', $po->supplier->supplier_id);
        $this->assertEquals('PT Supplier Alpha', $po->supplier->company_name);
    }

    /**
     * Test getPurchaseOrderByKeywords() with partial po_number search
     */
    public function test_get_purchase_order_by_keywords_with_partial_po_number_search()
    {
        // Arrange - Create test supplier
        $supplier = Supplier::create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Supplier Alpha',
            'address' => 'Jl. Supplier No. 1',
            'phone_number' => '021-1111111',
            'bank_account' => '1234567890',
        ]);

        // Create test purchase orders
        PurchaseOrder::create([
            'po_number' => 'PO0001',
            'supplier_id' => 'SUP001',
            'branch_id' => 1,
            'order_date' => '2024-01-15',
            'total' => 1000000,
            'status' => POStatus::Draft->value,
        ]);

        PurchaseOrder::create([
            'po_number' => 'PO0002',
            'supplier_id' => 'SUP001',
            'branch_id' => 1,
            'order_date' => '2024-01-16',
            'total' => 2000000,
            'status' => POStatus::Approved->value,
        ]);

        PurchaseOrder::create([
            'po_number' => 'PO0003',
            'supplier_id' => 'SUP001',
            'branch_id' => 1,
            'order_date' => '2024-01-17',
            'total' => 3000000,
            'status' => POStatus::Draft->value,
        ]);

        // Act - Search by partial po_number 'PO000'
        $result = PurchaseOrder::getPurchaseOrderByKeywords('PO000');

        // Assert - Should return all purchase orders with 'PO000' in po_number
        $this->assertNotNull($result);
        $this->assertEquals(3, $result->total());
        $this->assertCount(3, $result->items());
    }

    /**
     * Test getPurchaseOrderByKeywords() with case-insensitive search
     */
    public function test_get_purchase_order_by_keywords_case_insensitive_search()
    {
        // Arrange - Create test supplier
        $supplier = Supplier::create([
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Supplier Alpha',
            'address' => 'Jl. Supplier No. 1',
            'phone_number' => '021-1111111',
            'bank_account' => '1234567890',
        ]);

        // Create test purchase order
        PurchaseOrder::create([
            'po_number' => 'PO0001',
            'supplier_id' => 'SUP001',
            'branch_id' => 1,
            'order_date' => '2024-01-15',
            'total' => 1000000,
            'status' => POStatus::Draft->value,
        ]);

        // Act - Search with lowercase
        $result = PurchaseOrder::getPurchaseOrderByKeywords('draft');

        // Assert - Should return purchase orders regardless of case
        $this->assertNotNull($result);
        $this->assertEquals(1, $result->total());
        $this->assertCount(1, $result->items());
    }
}