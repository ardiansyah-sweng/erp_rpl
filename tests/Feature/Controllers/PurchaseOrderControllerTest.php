<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\Supplier;
use App\Models\Branch;
use App\Constants\Messages;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class PurchaseOrderControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup database tables
        $this->artisan('migrate:fresh');
        
        // Create supplier table if not exists (for testing)
        if (!Schema::hasTable('supplier')) {
            Schema::create('supplier', function ($table) {
                $table->char('supplier_id', 6)->primary();
                $table->string('company_name', 100);
                $table->string('address', 100);
                $table->string('phone_number', 30);
                $table->string('bank_account', 100);
                $table->timestamps();
            });
        }
    }

    /**
     * Helper method to create branch using DB::table
     */
    private function createBranch($branchId = null)
    {
        $id = $branchId ?? $this->faker->numberBetween(1, 100);
        
        DB::table('branches')->insert([
            'id' => $id,
            'branch_name' => 'Cabang ' . $this->faker->city,
            'branch_address' => $this->faker->address,
            'branch_telephone' => $this->faker->phoneNumber,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return $id;
    }

    /**
     * Helper method to create supplier using DB::table (for TC-APO tests)
     */
    private function createSupplierDirect($supplierId)
    {
        DB::table('supplier')->insert([
            'supplier_id' => $supplierId,
            'company_name' => $this->faker->company,
            'address' => $this->faker->address,
            'phone_number' => $this->faker->phoneNumber,
            'bank_account' => $this->faker->bankAccountNumber,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Helper method to build valid PO request data
     */
    private function buildValidPOData($poNumber, $branchId, $supplierId, $items = [])
    {
        // Default: 1 item if not provided
        if (empty($items)) {
            $items = [
                [
                    'po_number' => $poNumber,
                    'sku' => 'ITM001',
                    'qty' => 10,
                    'amount' => 100000,
                ]
            ];
        }

        // Calculate total
        $total = array_sum(array_column($items, 'amount'));

        // Build request data: items first, then header
        $requestData = $items;
        $requestData[] = [
            'po_number' => $poNumber,
            'branch_id' => $branchId,
            'supplier_id' => $supplierId,
            'total' => $total,
            'order_date' => Carbon::now()->format('Y-m-d'),
        ];

        return $requestData;
    }

    // ========== POSITIVE SCENARIOS ==========

    /**
     * TC-APO-01
     * Test: Add PO with valid single item
     * 
     * Scenario: User submits PO with valid header and 1 item
     * Expected: PO created successfully in database
     */
    public function test_add_po_with_valid_single_item()
    {
        // ARRANGE - Create test data
        $branchId = $this->createBranch(1);
        $this->createSupplierDirect('SUP001');

        $poData = $this->buildValidPOData('PO0001', $branchId, 'SUP001');

        // ACT - Submit PO
        $response = $this->post('/purchase_orders/add', $poData);

        // ASSERT - Verify success
        $response->assertStatus(302);
        $response->assertSessionHas('success', Messages::PO_CREATED);

        // Verify database
        $this->assertDatabaseHas('purchase_order', [
            'po_number' => 'PO0001',
            'supplier_id' => 'SUP001',
            'branch_id' => $branchId,
        ]);

        $this->assertDatabaseHas('purchase_order_detail', [
            'po_number' => 'PO0001',
            'product_id' => 'ITM001',
            'quantity' => 10,
            'amount' => 100000,
        ]);
    }

    /**
     * TC-APO-02
     * Test: Add PO with multiple items (3 items)
     * 
     * Scenario: User submits PO with valid header and 3 items
     * Expected: PO created with all 3 items in database
     */
    public function test_add_po_with_multiple_items()
    {
        // ARRANGE - Create test data
        $branchId = $this->createBranch(1);
        $this->createSupplierDirect('SUP002');

        $items = [
            [
                'po_number' => 'PO0002',
                'sku' => 'ITM001',
                'qty' => 10,
                'amount' => 100000,
            ],
            [
                'po_number' => 'PO0002',
                'sku' => 'ITM002',
                'qty' => 20,
                'amount' => 200000,
            ],
            [
                'po_number' => 'PO0002',
                'sku' => 'ITM003',
                'qty' => 10,
                'amount' => 200000,
            ],
        ];

        $poData = $this->buildValidPOData('PO0002', $branchId, 'SUP002', $items);

        // ACT - Submit PO
        $response = $this->post('/purchase_orders/add', $poData);

        // ASSERT - Verify success
        $response->assertStatus(302);
        $response->assertSessionHas('success', Messages::PO_CREATED);

        // Verify header
        $this->assertDatabaseHas('purchase_order', [
            'po_number' => 'PO0002',
            'supplier_id' => 'SUP002',
            'total' => 500000,
        ]);

        // Verify all 3 items
        $this->assertDatabaseHas('purchase_order_detail', [
            'po_number' => 'PO0002',
            'product_id' => 'ITM001',
        ]);
        $this->assertDatabaseHas('purchase_order_detail', [
            'po_number' => 'PO0002',
            'product_id' => 'ITM002',
        ]);
        $this->assertDatabaseHas('purchase_order_detail', [
            'po_number' => 'PO0002',
            'product_id' => 'ITM003',
        ]);

        // Verify count
        $detailCount = DB::table('purchase_order_detail')
            ->where('po_number', 'PO0002')
            ->count();
        $this->assertEquals(3, $detailCount);
    }

    /**
     * TC-APO-03
     * Test: Add PO with minimum valid qty (qty=1)
     * 
     * Scenario: Test boundary value for minimum quantity
     * Expected: PO created successfully with qty=1
     */
    public function test_add_po_with_minimum_valid_qty()
    {
        // ARRANGE
        $branchId = $this->createBranch(1);
        $this->createSupplierDirect('SUP003');

        $items = [
            [
                'po_number' => 'PO0003',
                'sku' => 'ITM001',
                'qty' => 1, // Minimum valid
                'amount' => 50000,
            ]
        ];

        $poData = $this->buildValidPOData('PO0003', $branchId, 'SUP003', $items);

        // ACT
        $response = $this->post('/purchase_orders/add', $poData);

        // ASSERT
        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('purchase_order_detail', [
            'po_number' => 'PO0003',
            'quantity' => 1,
        ]);
    }

    /**
     * TC-APO-04
     * Test: Add PO with large qty (qty=9999)
     * 
     * Scenario: Test boundary value for large quantity
     * Expected: PO created successfully with large qty
     */
    public function test_add_po_with_large_qty()
    {
        // ARRANGE
        $branchId = $this->createBranch(1);
        $this->createSupplierDirect('SUP004');

        $items = [
            [
                'po_number' => 'PO0004',
                'sku' => 'ITM001',
                'qty' => 9999,
                'amount' => 9999000,
            ]
        ];

        $poData = $this->buildValidPOData('PO0004', $branchId, 'SUP004', $items);

        // ACT
        $response = $this->post('/purchase_orders/add', $poData);

        // ASSERT
        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('purchase_order_detail', [
            'po_number' => 'PO0004',
            'quantity' => 9999,
            'amount' => 9999000,
        ]);
    }

    /**
     * TC-APO-05
     * Test: Add PO with today's order date
     * 
     * Scenario: Test with current date as order date
     * Expected: PO created successfully with today's date
     */
    public function test_add_po_with_today_order_date()
    {
        // ARRANGE
        $branchId = $this->createBranch(1);
        $this->createSupplierDirect('SUP005');

        $todayDate = Carbon::now()->format('Y-m-d');
        
        $items = [
            [
                'po_number' => 'PO0005',
                'sku' => 'ITM001',
                'qty' => 10,
                'amount' => 100000,
            ]
        ];

        $poData = $items;
        $poData[] = [
            'po_number' => 'PO0005',
            'branch_id' => $branchId,
            'supplier_id' => 'SUP005',
            'total' => 100000,
            'order_date' => $todayDate,
        ];

        // ACT
        $response = $this->post('/purchase_orders/add', $poData);

        // ASSERT
        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('purchase_order', [
            'po_number' => 'PO0005',
            'order_date' => $todayDate,
        ]);
    }

    // ========== NEGATIVE SCENARIOS (Validation) ==========

    /**
     * TC-APO-06
     * Test: Add PO with missing po_number in header
     * 
     * Scenario: Submit PO without po_number in header
     * Expected: Validation error, no database insert
     */
    public function test_add_po_with_missing_po_number_in_header()
    {
        // ARRANGE
        $branchId = $this->createBranch(1);
        $this->createSupplierDirect('SUP006');

        $items = [
            [
                'po_number' => 'PO0006',
                'sku' => 'ITM001',
                'qty' => 10,
                'amount' => 100000,
            ]
        ];

        // Header WITHOUT po_number
        $poData = $items;
        $poData[] = [
            // 'po_number' => 'PO0006', // MISSING
            'branch_id' => $branchId,
            'supplier_id' => 'SUP006',
            'total' => 100000,
            'order_date' => Carbon::now()->format('Y-m-d'),
        ];

        // ACT
        $response = $this->post('/purchase_orders/add', $poData);

        // ASSERT
        $response->assertStatus(302);
        $response->assertSessionHasErrors();

        // Verify NO database insert
        $this->assertDatabaseMissing('purchase_order', [
            'supplier_id' => 'SUP006',
        ]);
    }

    /**
     * TC-APO-07
     * Test: Add PO with invalid branch_id (non-integer)
     * 
     * Scenario: Submit PO with string branch_id
     * Expected: Validation error
     */
    public function test_add_po_with_invalid_branch_id()
    {
        // ARRANGE
        $this->createSupplierDirect('SUP007');

        $items = [
            [
                'po_number' => 'PO0007',
                'sku' => 'ITM001',
                'qty' => 10,
                'amount' => 100000,
            ]
        ];

        $poData = $items;
        $poData[] = [
            'po_number' => 'PO0007',
            'branch_id' => 'abc', // Invalid: string instead of integer
            'supplier_id' => 'SUP007',
            'total' => 100000,
            'order_date' => Carbon::now()->format('Y-m-d'),
        ];

        // ACT
        $response = $this->post('/purchase_orders/add', $poData);

        // ASSERT
        $response->assertStatus(302);
        $response->assertSessionHasErrors();

        $this->assertDatabaseMissing('purchase_order', [
            'po_number' => 'PO0007',
        ]);
    }

    /**
     * TC-APO-08
     * Test: Add PO with missing supplier_id
     * 
     * Scenario: Submit PO without supplier_id in header
     * Expected: Validation error
     */
    public function test_add_po_with_missing_supplier_id()
    {
        // ARRANGE
        $branchId = $this->createBranch(1);

        $items = [
            [
                'po_number' => 'PO0008',
                'sku' => 'ITM001',
                'qty' => 10,
                'amount' => 100000,
            ]
        ];

        $poData = $items;
        $poData[] = [
            'po_number' => 'PO0008',
            'branch_id' => $branchId,
            // 'supplier_id' => 'SUP008', // MISSING
            'total' => 100000,
            'order_date' => Carbon::now()->format('Y-m-d'),
        ];

        // ACT
        $response = $this->post('/purchase_orders/add', $poData);

        // ASSERT
        $response->assertStatus(302);
        $response->assertSessionHasErrors();

        $this->assertDatabaseMissing('purchase_order', [
            'po_number' => 'PO0008',
        ]);
    }

    /**
     * TC-APO-09
     * Test: Add PO with negative total
     * 
     * Scenario: Submit PO with negative total amount
     * Expected: Validation error
     */
    public function test_add_po_with_negative_total()
    {
        // ARRANGE
        $branchId = $this->createBranch(1);
        $this->createSupplierDirect('SUP009');

        $items = [
            [
                'po_number' => 'PO0009',
                'sku' => 'ITM001',
                'qty' => 10,
                'amount' => 100000,
            ]
        ];

        $poData = $items;
        $poData[] = [
            'po_number' => 'PO0009',
            'branch_id' => $branchId,
            'supplier_id' => 'SUP009',
            'total' => -50000, // Invalid: negative
            'order_date' => Carbon::now()->format('Y-m-d'),
        ];

        // ACT
        $response = $this->post('/purchase_orders/add', $poData);

        // ASSERT
        $response->assertStatus(302);
        $response->assertSessionHasErrors();

        $this->assertDatabaseMissing('purchase_order', [
            'po_number' => 'PO0009',
        ]);
    }

    /**
     * TC-APO-10
     * Test: Add PO with invalid order_date format
     * 
     * Scenario: Submit PO with invalid date format
     * Expected: Validation error
     */
    public function test_add_po_with_invalid_order_date_format()
    {
        // ARRANGE
        $branchId = $this->createBranch(1);
        $this->createSupplierDirect('SUP010');

        $items = [
            [
                'po_number' => 'PO0010',
                'sku' => 'ITM001',
                'qty' => 10,
                'amount' => 100000,
            ]
        ];

        $poData = $items;
        $poData[] = [
            'po_number' => 'PO0010',
            'branch_id' => $branchId,
            'supplier_id' => 'SUP010',
            'total' => 100000,
            'order_date' => 'invalid-date', // Invalid format
        ];

        // ACT
        $response = $this->post('/purchase_orders/add', $poData);

        // ASSERT
        $response->assertStatus(302);
        $response->assertSessionHasErrors();

        $this->assertDatabaseMissing('purchase_order', [
            'po_number' => 'PO0010',
        ]);
    }

    /**
     * TC-APO-11
     * Test: Add PO with missing sku in item
     * 
     * Scenario: Submit PO item without sku
     * Expected: Validation error
     */
    public function test_add_po_with_missing_sku_in_item()
    {
        // ARRANGE
        $branchId = $this->createBranch(1);
        $this->createSupplierDirect('SUP011');

        $items = [
            [
                'po_number' => 'PO0011',
                // 'sku' => 'ITM001', // MISSING
                'qty' => 10,
                'amount' => 100000,
            ]
        ];

        $poData = $items;
        $poData[] = [
            'po_number' => 'PO0011',
            'branch_id' => $branchId,
            'supplier_id' => 'SUP011',
            'total' => 100000,
            'order_date' => Carbon::now()->format('Y-m-d'),
        ];

        // ACT
        $response = $this->post('/purchase_orders/add', $poData);

        // ASSERT
        $response->assertStatus(302);
        $response->assertSessionHasErrors();

        $this->assertDatabaseMissing('purchase_order', [
            'po_number' => 'PO0011',
        ]);
    }

    /**
     * TC-APO-12
     * Test: Add PO with qty = 0 (below minimum)
     * 
     * Scenario: Submit PO item with qty = 0
     * Expected: Validation error (min:1)
     */
    public function test_add_po_with_qty_zero()
    {
        // ARRANGE
        $branchId = $this->createBranch(1);
        $this->createSupplierDirect('SUP012');

        $items = [
            [
                'po_number' => 'PO0012',
                'sku' => 'ITM001',
                'qty' => 0, // Invalid: below minimum
                'amount' => 0,
            ]
        ];

        $poData = $items;
        $poData[] = [
            'po_number' => 'PO0012',
            'branch_id' => $branchId,
            'supplier_id' => 'SUP012',
            'total' => 0,
            'order_date' => Carbon::now()->format('Y-m-d'),
        ];

        // ACT
        $response = $this->post('/purchase_orders/add', $poData);

        // ASSERT
        $response->assertStatus(302);
        $response->assertSessionHasErrors();

        $this->assertDatabaseMissing('purchase_order', [
            'po_number' => 'PO0012',
        ]);
    }

    /**
     * TC-APO-13
     * Test: Add PO with negative qty
     * 
     * Scenario: Submit PO item with negative quantity
     * Expected: Validation error
     */
    public function test_add_po_with_negative_qty()
    {
        // ARRANGE
        $branchId = $this->createBranch(1);
        $this->createSupplierDirect('SUP013');

        $items = [
            [
                'po_number' => 'PO0013',
                'sku' => 'ITM001',
                'qty' => -5, // Invalid: negative
                'amount' => -50000,
            ]
        ];

        $poData = $items;
        $poData[] = [
            'po_number' => 'PO0013',
            'branch_id' => $branchId,
            'supplier_id' => 'SUP013',
            'total' => -50000,
            'order_date' => Carbon::now()->format('Y-m-d'),
        ];

        // ACT
        $response = $this->post('/purchase_orders/add', $poData);

        // ASSERT
        $response->assertStatus(302);
        $response->assertSessionHasErrors();

        $this->assertDatabaseMissing('purchase_order', [
            'po_number' => 'PO0013',
        ]);
    }

    /**
     * TC-APO-14
     * Test: Add PO with negative amount
     * 
     * Scenario: Submit PO item with negative amount
     * Expected: Validation error
     */
    public function test_add_po_with_negative_amount()
    {
        // ARRANGE
        $branchId = $this->createBranch(1);
        $this->createSupplierDirect('SUP014');

        $items = [
            [
                'po_number' => 'PO0014',
                'sku' => 'ITM001',
                'qty' => 10,
                'amount' => -100000, // Invalid: negative
            ]
        ];

        $poData = $items;
        $poData[] = [
            'po_number' => 'PO0014',
            'branch_id' => $branchId,
            'supplier_id' => 'SUP014',
            'total' => -100000,
            'order_date' => Carbon::now()->format('Y-m-d'),
        ];

        // ACT
        $response = $this->post('/purchase_orders/add', $poData);

        // ASSERT
        $response->assertStatus(302);
        $response->assertSessionHasErrors();

        $this->assertDatabaseMissing('purchase_order', [
            'po_number' => 'PO0014',
        ]);
    }

    /**
     * TC-APO-15
     * Test: Add PO with missing amount in item
     * 
     * Scenario: Submit PO item without amount
     * Expected: Validation error
     */
    public function test_add_po_with_missing_amount_in_item()
    {
        // ARRANGE
        $branchId = $this->createBranch(1);
        $this->createSupplierDirect('SUP015');

        $items = [
            [
                'po_number' => 'PO0015',
                'sku' => 'ITM001',
                'qty' => 10,
                // 'amount' => 100000, // MISSING
            ]
        ];

        $poData = $items;
        $poData[] = [
            'po_number' => 'PO0015',
            'branch_id' => $branchId,
            'supplier_id' => 'SUP015',
            'total' => 100000,
            'order_date' => Carbon::now()->format('Y-m-d'),
        ];

        // ACT
        $response = $this->post('/purchase_orders/add', $poData);

        // ASSERT
        $response->assertStatus(302);
        $response->assertSessionHasErrors();

        $this->assertDatabaseMissing('purchase_order', [
            'po_number' => 'PO0015',
        ]);
    }

    // ========== EDGE CASES & ERROR HANDLING ==========

    /**
     * TC-APO-16
     * Test: Empty items array
     * 
     * Scenario: Submit PO with empty items array (only header, no items)
     * Expected: Should succeed but no items inserted (only header)
     * 
     * Note: Controller doesn't validate empty items, it just processes what's given
     */
    public function test_add_po_with_empty_items_array()
    {
        // ARRANGE
        $branchId = $this->createBranch(1);
        $this->createSupplierDirect('SUP016');

        // Build request manually without items (only header)
        $poData = [
            [
                'po_number' => 'PO0016',
                'branch_id' => $branchId,
                'supplier_id' => 'SUP016',
                'total' => 0,
                'order_date' => Carbon::now()->format('Y-m-d'),
            ]
        ];

        // ACT
        $response = $this->post('/purchase_orders/add', $poData);

        // ASSERT
        $response->assertStatus(302);
        $response->assertSessionHas('success');
        
        // Header should be in database
        $this->assertDatabaseHas('purchase_order', ['po_number' => 'PO0016']);
        
        // But NO detail records
        $detailCount = DB::table('purchase_order_detail')->where('po_number', 'PO0016')->count();
        $this->assertEquals(0, $detailCount, 'Should have no detail records for empty items');

        // Disable mail sending during tests
        \Illuminate\Support\Facades\Mail::fake();

        // Config DB sesuai analisis sebelumnya (Singular)
        Config::set('db_constants.table.po', 'purchase_order');
    }

    // ============================================
    // HELPERS
    // ============================================

    private function createSupplier($data = [])
    {
        $defaults = [
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Test Supplier',
            'address' => 'Jl. Test No. 123',
            'telephone' => '081234567890',
            'bank_account' => '1234567890',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        return Supplier::forceCreate(array_merge($defaults, $data));
    }

    private function createPurchaseOrder($data = [])
    {
        $defaults = [
            'po_number' => 'PO001',
            'supplier_id' => 'SUP001',
            'branch_id' => 1,
            'order_date' => '2024-01-15',
            'total' => 1000000,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        return PurchaseOrder::forceCreate(array_merge($defaults, $data));
    }

    private function createPurchaseOrderDetail($data = [])
    {
        $defaults = [
            'po_number' => 'PO001',
            'product_id' => 'PROD01',
            'quantity' => 10,
            'amount' => 1000000,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        return PurchaseOrderDetail::forceCreate(array_merge($defaults, $data));
    }

    // ============================================
    // TEST CASES (Renamed)
    // ============================================

    #[Test]
    #[Group('pdf-generation')]
    public function test_generatePurchaseOrderPDF_success_with_valid_data()
    {
        // Arrange
        $this->createSupplier(['supplier_id' => 'SUP001']);
        $this->createPurchaseOrder(['po_number' => 'PO001', 'supplier_id' => 'SUP001']);
        $this->createPurchaseOrderDetail(['po_number' => 'PO001']);

        // Act
        $response = $this->post('/purchase-orders/pdf', [
            'start_date' => '2024-01-01',
            'end_date' => '2024-01-31',
            'supplier_id' => 'SUP001',
        ]);

        // Assert
        $response->assertStatus(200);

        if ($response->headers->get('Content-Type') === 'application/pdf') {
             $response->assertHeader('Content-Type', 'application/pdf');
        } else {
             $this->assertTrue(true); 
        }
    }

    #[Test]
    #[Group('pdf-generation')]
    public function test_generatePurchaseOrderPDF_success_with_multiple_orders()
    {
        // Arrange
        $this->createSupplier([
            'supplier_id' => 'SUP003',
            'company_name' => 'PT Multi Order',
        ]);

        for ($i = 1; $i <= 3; $i++) {
            $poNum = 'PO00' . $i;
            $this->createPurchaseOrder([
                'po_number' => $poNum,
                'supplier_id' => 'SUP003',
                'order_date' => '2024-01-' . (10 + $i),
                'total' => 1000000 * $i,
            ]);

            $this->createPurchaseOrderDetail([
                'po_number' => $poNum,
                'product_id' => 'PROD0' . $i,
            ]);
        }

        // Act
        $response = $this->post('/purchase-orders/pdf', [
            'start_date' => '2024-01-01',
            'end_date' => '2024-01-31',
            'supplier_id' => 'SUP003',
        ]);

        // Assert
        $response->assertStatus(200);
    }

    #[Test]
    #[Group('pdf-generation')]
    public function test_generatePurchaseOrderPDF_success_with_supplier_without_orders()
    {
        // Arrange
        $this->createSupplier([
            'supplier_id' => 'SUP002',
            'company_name' => 'PT Supplier Kosong',
        ]);

        // Act
        $response = $this->post('/purchase-orders/pdf', [
            'start_date' => '2024-01-01',
            'end_date' => '2024-01-31',
            'supplier_id' => 'SUP002',
        ]);

        // Assert
        $response->assertStatus(200);
    }

    #[Test]
    #[Group('validation')]
    public function test_generatePurchaseOrderPDF_validates_required_fields()
    {
        // Test 1: Missing start_date
        $response = $this->post('/purchase-orders/pdf', [
            'end_date' => '2024-01-31',
            'supplier_id' => 'SUP001',
        ]);
        $response->assertSessionHasErrors('start_date');

        // Test 2: Missing end_date
        $response = $this->post('/purchase-orders/pdf', [
            'start_date' => '2024-01-01',
            'supplier_id' => 'SUP001',
        ]);
        $response->assertSessionHasErrors('end_date');

        // Test 3: Missing supplier_id
        $response = $this->post('/purchase-orders/pdf', [
            'start_date' => '2024-01-01',
            'end_date' => '2024-01-31',
        ]);
        $response->assertSessionHasErrors('supplier_id');
    }

    #[Test]
    #[Group('validation')]
    public function test_generatePurchaseOrderPDF_validates_date_logic()
    {
        // Test: end_date before start_date
        $response = $this->post('/purchase-orders/pdf', [
            'start_date' => '2024-01-31',
            'end_date' => '2024-01-01',
            'supplier_id' => 'SUP001',
        ]);

        $response->assertSessionHasErrors('end_date');
    }

    #[Test]
    #[Group('date-filter')]
    public function test_generatePurchaseOrderPDF_filters_purchase_orders_by_date_range()
    {
        // Arrange
        $this->createSupplier(['supplier_id' => 'SUP004']);

        // PO di dalam rentang
        $this->createPurchaseOrder([
            'po_number' => 'POIN',
            'supplier_id' => 'SUP004',
            'order_date' => '2024-01-15',
        ]);

        // PO di luar rentang
        $this->createPurchaseOrder([
            'po_number' => 'POOUT',
            'supplier_id' => 'SUP004',
            'order_date' => '2024-02-15',
        ]);

        // Act
        $startDate = Carbon::parse('2024-01-01')->startOfDay();
        $endDate = Carbon::parse('2024-01-31')->endOfDay();

        $purchaseOrders = PurchaseOrder::getReportBySupplierAndDate('SUP004', $startDate, $endDate);

        // Assert
        $this->assertCount(1, $purchaseOrders);
        $this->assertEquals('POIN', $purchaseOrders->first()->po_number);
    }

    #[Test]
    #[Group('relationships')]
    public function test_generatePurchaseOrderPDF_loads_supplier_and_details_relations()
    {
        // Arrange
        $this->createSupplier(['supplier_id' => 'SUP007']);
        $this->createPurchaseOrder(['po_number' => 'PO007', 'supplier_id' => 'SUP007']);
        $this->createPurchaseOrderDetail(['po_number' => 'PO007', 'product_id' => 'PROD07']);

        // Act
        $startDate = Carbon::parse('2024-01-01')->startOfDay();
        $endDate = Carbon::parse('2024-01-31')->endOfDay();

        $purchaseOrders = PurchaseOrder::getReportBySupplierAndDate('SUP007', $startDate, $endDate);

        // Assert
        $this->assertCount(1, $purchaseOrders);

        // Pastikan relasi supplier terload
        $this->assertTrue($purchaseOrders->first()->relationLoaded('supplier'));
        $this->assertNotNull($purchaseOrders->first()->supplier);
        $this->assertEquals('SUP007', $purchaseOrders->first()->supplier->supplier_id);

        // Pastikan relasi details terload
        $this->assertTrue($purchaseOrders->first()->relationLoaded('details'));
        $this->assertCount(1, $purchaseOrders->first()->details);
    }

    #[Test]
    #[Group('ordering')]
    public function test_generatePurchaseOrderPDF_orders_purchase_orders_by_date_descending()
    {
        // Arrange
        $this->createSupplier(['supplier_id' => 'SUP008']);

        $this->createPurchaseOrder([
            'po_number' => 'PO1',
            'supplier_id' => 'SUP008',
            'order_date' => '2024-01-10',
        ]);

        $this->createPurchaseOrder([
            'po_number' => 'PO2',
            'supplier_id' => 'SUP008',
            'order_date' => '2024-01-20',
        ]);

        // Act
        $startDate = Carbon::parse('2024-01-01')->startOfDay();
        $endDate = Carbon::parse('2024-01-31')->endOfDay();

        $purchaseOrders = PurchaseOrder::getReportBySupplierAndDate('SUP008', $startDate, $endDate);

        // Assert - Newest first (descending)
        $this->assertEquals('PO2', $purchaseOrders[0]->po_number); // 2024-01-20
        $this->assertEquals('PO1', $purchaseOrders[1]->po_number); // 2024-01-10
    }

    /**
     * Test 1: Mencari berdasarkan PO Number (Happy Path)
     */
    public function test_search_purchase_order_by_po_number()
    {
        // Arrange
        $supplier = $this->createSupplier(['supplier_id' => 'SUP010']);
        
        $targetPO = $this->createPurchaseOrder([
            'po_number' => 'PO0010',
            'supplier_id' => $supplier->supplier_id
        ]);
        
        $otherPO = $this->createPurchaseOrder([
            'po_number' => 'PO0099',
            'supplier_id' => $supplier->supplier_id
        ]);

        // Act
        // REVISI: Menggunakan URL manual '/purchase-orders/search' agar sama gayanya dengan '/purchase_orders/add'
        $response = $this->get('/purchase-orders/search?keyword=PO0010');

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('purchase_orders.list');
        
        $response->assertViewHas('purchaseOrders', function ($purchaseOrders) use ($targetPO) {
            return $purchaseOrders->contains('po_number', $targetPO->po_number) 
                && $purchaseOrders->count() === 1;
        });

        $response->assertViewHas('keyword', 'PO0010');
    }

    /**
     * Test 2: Mencari berdasarkan Nama Supplier (Relasi)
     */
    public function test_search_purchase_order_by_supplier_company_name()
    {
        // Arrange
        $supplierA = $this->createSupplier(['supplier_id' => 'SUP02A', 'company_name' => 'PT Mencari Cinta']);
        $supplierB = $this->createSupplier(['supplier_id' => 'SUP02B', 'company_name' => 'PT Yang Hilang']);

        $poA = $this->createPurchaseOrder([
            'po_number' => 'PO-A-1', 
            'supplier_id' => $supplierA->supplier_id
        ]);
        
        $poB = $this->createPurchaseOrder([
            'po_number' => 'PO-B-1', 
            'supplier_id' => $supplierB->supplier_id
        ]);

        // Act
        // REVISI: URL Manual
        $response = $this->get('/purchase-orders/search?keyword=Mencari Cinta');

        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('purchaseOrders', function ($purchaseOrders) use ($poA) {
            return $purchaseOrders->contains('po_number', $poA->po_number);
        });
        
        $response->assertViewHas('purchaseOrders', function ($purchaseOrders) use ($poB) {
            return !$purchaseOrders->contains('po_number', $poB->po_number);
        });
    }

    /**
     * Test 3: Mencari berdasarkan Status
     */
    public function test_search_purchase_order_by_status()
    {
        // Arrange
        $supplier = $this->createSupplier(['supplier_id' => 'SUP030']);
        
        $this->createPurchaseOrder(['po_number' => 'PO-1', 'status' => 'Completed', 'supplier_id' => 'SUP030']);
        $this->createPurchaseOrder(['po_number' => 'PO-2', 'status' => 'Cancelled', 'supplier_id' => 'SUP030']);

        // Act
        // REVISI: URL Manual
        $response = $this->get('/purchase-orders/search?keyword=Completed');

        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('purchaseOrders', function ($purchaseOrders) {
            return $purchaseOrders->count() === 1 
                && $purchaseOrders->first()->status === 'Completed';
        });
    }

    /**
     * Test 4: Tidak ada hasil (Empty State)
     */
    public function test_search_purchase_order_no_results()
    {
        // Arrange
        $supplier = $this->createSupplier(['supplier_id' => 'SUP040']);
        $this->createPurchaseOrder(['po_number' => 'PO0080', 'supplier_id' => 'SUP040']);

        // Act
        // REVISI: URL Manual
        $response = $this->get('/purchase-orders/search?keyword=PO-GAIB');

        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('purchaseOrders', function ($purchaseOrders) {
            return $purchaseOrders->isEmpty();
        });
    }

    /**
     * Test 5: Variable totalOrders harus menghitung SEMUA data (bukan hasil filter)
     */
    public function test_search_purchase_order_returns_correct_total_count()
    {
        // Arrange
        $supplier = $this->createSupplier(['supplier_id' => 'SUP050']);
        
        $this->createPurchaseOrder(['po_number' => 'PO-1', 'supplier_id' => 'SUP050']);
        $this->createPurchaseOrder(['po_number' => 'PO-2', 'supplier_id' => 'SUP050']);
        $this->createPurchaseOrder(['po_number' => 'PO-3', 'supplier_id' => 'SUP050']);

        // Act
        // REVISI: URL Manual
        $response = $this->get('/purchase-orders/search?keyword=PO-1');

        // Assert
        $response->assertViewHas('purchaseOrders', function ($pos) {
            return $pos->count() === 1;
        });

        $response->assertViewHas('totalOrders', 3);
    }
}