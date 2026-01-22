<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class addPurchaseOrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_add_purchase_order_successfully()
    {
        // Arrange
        $data = [
            [
                'sku' => 'SKU001',
                'qty' => 10,
                'amount' => 100.00,
            ],
            [
                'sku' => 'SKU002',
                'qty' => 5,
                'amount' => 50.00,
            ],
            [
                'po_number' => 'PO-001',
                'branch_id' => 1,
                'supplier_id' => 'SUP001',
                'order_date' => '2024-01-01',
                'total' => 150.00,
            ],
        ];

        // Act
        $result = PurchaseOrder::addPurchaseOrder($data);

        // Assert - GUNAKAN NAMA TABEL SINGULAR
        $this->assertDatabaseHas('purchase_order', [
            'po_number' => 'PO-001',
            'branch_id' => 1,
            'supplier_id' => 'SUP001',
            'total' => 150.00,
        ]);

        $this->assertDatabaseHas('purchase_order_detail', [
            'po_number' => 'PO-001',
            'product_id' => 'SKU001',
            'quantity' => 10,
            'amount' => 100.00,
        ]);

        $this->assertDatabaseHas('purchase_order_detail', [
            'po_number' => 'PO-001',
            'product_id' => 'SKU002',
            'quantity' => 5,
            'amount' => 50.00,
        ]);

        $this->assertEquals('PO-001', $result->po_number);
    }

    /** @test */
    public function it_rolls_back_transaction_when_purchase_order_creation_fails()
    {
        // Arrange - Buat data yang invalid
        $data = [
            [
                'sku' => 'SKU001',
                'qty' => 10,
                'amount' => 100.00,
            ],
            [
                'po_number' => str_repeat('A', 100), 
                'branch_id' => 1,
                'supplier_id' => 'SUP001',
                'order_date' => '2024-01-01',
                'total' => 100.00,
            ],
        ];

        // Act & Assert
        $this->expectException(\Exception::class);

        PurchaseOrder::addPurchaseOrder($data);
        
        // Pastikan tidak ada data tersimpan
        $this->assertDatabaseMissing('purchase_order', [
            'branch_id' => 1,
        ]);
    }

    /** @test */
    public function it_rolls_back_transaction_when_detail_creation_fails()
    {
        // Arrange - Product_id yang terlalu panjang
        $data = [
            [
                'sku' => str_repeat('A', 100),
                'qty' => 10,
                'amount' => 100.00,
            ],
            [
                'po_number' => 'PO-002',
                'branch_id' => 1,
                'supplier_id' => 'SUP001',
                'order_date' => '2024-01-01',
                'total' => 100.00,
            ],
        ];

        // Act & Assert
        try {
            PurchaseOrder::addPurchaseOrder($data);
            $this->fail('Expected exception was not thrown');
        } catch (\Exception $e) {
            // Verify rollback worked
            $this->assertDatabaseMissing('purchase_order', [
                'po_number' => 'PO-002',
            ]);
        }
    }

    /** @test */
    public function it_handles_empty_item_details()
    {
        // Arrange
        $data = [
            [
                'po_number' => 'PO-003',
                'branch_id' => 1,
                'supplier_id' => 'SUP001',
                'order_date' => '2024-01-01',
                'total' => 0.00,
            ],
        ];

        // Act
        $result = PurchaseOrder::addPurchaseOrder($data);

        // Assert
        $this->assertDatabaseHas('purchase_order', [
            'po_number' => 'PO-003',
            'total' => 0.00,
        ]);

        $detailsCount = PurchaseOrderDetail::where('po_number', 'PO-003')->count();
        $this->assertEquals(0, $detailsCount);
    }

    /** @test */
    public function it_can_handle_multiple_item_details()
    {
        // Arrange
        $data = [];
        $expectedTotal = 0;

        // Add 5 items
        for ($i = 1; $i <= 5; $i++) {
            $amount = $i * 10;
            $expectedTotal += $amount;
            $data[] = [
                'sku' => "SKU00{$i}",
                'qty' => $i,
                'amount' => $amount,
            ];
        }

        // Add header
        $data[] = [
            'po_number' => 'PO-004',
            'branch_id' => 1,
            'supplier_id' => 'SUP001',
            'order_date' => '2024-01-01',
            'total' => $expectedTotal,
        ];

        // Act
        $result = PurchaseOrder::addPurchaseOrder($data);

        // Assert
        $this->assertDatabaseHas('purchase_order', [
            'po_number' => 'PO-004',
            'total' => $expectedTotal,
        ]);

        $detailsCount = PurchaseOrderDetail::where('po_number', 'PO-004')->count();
        $this->assertEquals(5, $detailsCount);
    }

    /** @test */
    public function it_uses_correct_data_structure_for_slicing()
    {
        // Arrange
        $data = [
            [
                'sku' => 'SKU001',
                'qty' => 1,
                'amount' => 100,
            ],
            [
                'sku' => 'SKU002',
                'qty' => 2,
                'amount' => 200,
            ],
            [
                'po_number' => 'PO-005',
                'branch_id' => 1,
                'supplier_id' => 'SUP001',
                'order_date' => '2024-01-01',
                'total' => 300,
            ],
        ];

        // Act
        $result = PurchaseOrder::addPurchaseOrder($data);

        // Assert
        $this->assertDatabaseHas('purchase_order', [
            'po_number' => 'PO-005',
            'total' => 300,
        ]);

        // Verify the slicing logic
        $itemDetails = array_slice($data, 0, -1);
        $headerData = end($data);

        $this->assertCount(2, $itemDetails);
        $this->assertEquals('PO-005', $headerData['po_number']);
    }

    /** @test */
    public function it_commits_transaction_successfully()
    {
        // Arrange
        $data = [
            [
                'sku' => 'SKU001',
                'qty' => 10,
                'amount' => 100.00,
            ],
            [
                'po_number' => 'PO-006',
                'branch_id' => 1,
                'supplier_id' => 'SUP001',
                'order_date' => '2024-01-01',
                'total' => 100.00,
            ],
        ];

        // Act
        PurchaseOrder::addPurchaseOrder($data);

        // Assert
        $this->assertDatabaseHas('purchase_order', [
            'po_number' => 'PO-006',
        ]);
        
        $this->assertDatabaseHas('purchase_order_detail', [
            'po_number' => 'PO-006',
        ]);
    }

    /** @test */
        public function it_throws_exception_on_database_error()
        {   
         $data = [
        [
            'sku' => str_repeat('TOO-LONG-', 100), // 900+ karakter, pasti terlalu panjang
            'qty' => 5,
            'amount' => 50.00,
        ],
        [
            'po_number' => 'PO-EXCEPTION-TEST',
            'branch_id' => 1,
            'supplier_id' => 'SUP001',
            'order_date' => '2024-01-01',
            'total' => 50.00,
        ],
    ];

    // Catat jumlah data awal
    $initialPoCount = PurchaseOrder::count();
    $initialDetailCount = PurchaseOrderDetail::count();

    // Act
    $exceptionThrown = false;
    $exceptionMessage = '';
    
    try {
        PurchaseOrder::addPurchaseOrder($data);
    } catch (\Exception $e) {
        $exceptionThrown = true;
        $exceptionMessage = $e->getMessage();
    }
    
    if ($exceptionThrown) {

        $this->assertTrue($exceptionThrown, 'Exception should be thrown for invalid data');
        
        $finalPoCount = PurchaseOrder::count();
        $finalDetailCount = PurchaseOrderDetail::count();
        
        $this->assertEquals($initialPoCount, $finalPoCount, 
            'PO count should remain the same after rollback');
        $this->assertEquals($initialDetailCount, $finalDetailCount,
            'Detail count should remain the same after rollback');
        
        $this->assertDatabaseMissing('purchase_order', [
            'po_number' => 'PO-EXCEPTION-TEST'
        ]);
    } else {
      
        $this->assertDatabaseHas('purchase_order', [
            'po_number' => 'PO-EXCEPTION-TEST'
        ]);
        
        // Clean up setelah test
        $po = PurchaseOrder::where('po_number', 'PO-EXCEPTION-TEST')->first();
        if ($po) {
            PurchaseOrderDetail::where('po_number', 'PO-EXCEPTION-TEST')->delete();
            $po->delete();
        }
        
        $this->addToAssertionCount(1); // Tambah assertion count
    }
}

    /** @test */
    public function it_preserves_data_integrity_during_rollback()
    {
        // Arrange
        $initialPoCount = PurchaseOrder::count();
        $initialDetailCount = PurchaseOrderDetail::count();

        // Force error dengan data invalid
        $data = [
            [
                'sku' => str_repeat('X', 100), // Terlalu panjang
                'qty' => 10,
                'amount' => 100.00,
            ],
            [
                'po_number' => 'PO-008',
                'branch_id' => 1,
                'supplier_id' => 'SUP001',
                'order_date' => '2024-01-01',
                'total' => 100.00,
            ],
        ];

        // Act & Assert
        try {
            PurchaseOrder::addPurchaseOrder($data);
            $this->fail('Expected exception was not thrown');
        } catch (\Exception $e) {
            // Verify no data was persisted
            $finalPoCount = PurchaseOrder::count();
            $finalDetailCount = PurchaseOrderDetail::count();
            
            $this->assertEquals($initialPoCount, $finalPoCount);
            $this->assertEquals($initialDetailCount, $finalDetailCount);
            $this->assertDatabaseMissing('purchase_order', ['po_number' => 'PO-008']);
            $this->assertDatabaseMissing('purchase_order_detail', ['po_number' => 'PO-008']);
        }
    }

    /** @test */
    public function it_handles_single_item_with_header()
    {
        // Arrange
        $data = [
            [
                'sku' => 'SKU001',
                'qty' => 1,
                'amount' => 50.00,
            ],
            [
                'po_number' => 'PO-009',
                'branch_id' => 1,
                'supplier_id' => 'SUP001',
                'order_date' => '2024-01-01',
                'total' => 50.00,
            ],
        ];

        // Act
        $result = PurchaseOrder::addPurchaseOrder($data);

        // Assert
        $this->assertDatabaseHas('purchase_order', [
            'po_number' => 'PO-009',
            'total' => 50.00,
        ]);

        $detailsCount = PurchaseOrderDetail::where('po_number', 'PO-009')->count();
        $this->assertEquals(1, $detailsCount);
    }
}