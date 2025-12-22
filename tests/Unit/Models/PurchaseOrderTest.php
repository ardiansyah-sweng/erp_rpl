<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PurchaseOrderTest extends TestCase
{
    use DatabaseTransactions; // Gunakan ini untuk rollback setelah tiap test

    public function test_it_can_add_purchase_order_successfully(): void
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

        // Assert - PERBAIKAN: Gunakan nama tabel singular
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

    public function test_it_rolls_back_transaction_when_purchase_order_creation_fails(): void
    {
        // Arrange - Buat data yang invalid (po_number terlalu panjang untuk char(6))
        $data = [
            [],
            [
                'po_number' => 'PO-TOOLONG', // Lebih dari 6 karakter
                'branch_id' => 1,
                'supplier_id' => 'SUP001',
                'order_date' => '2024-01-01',
                'total' => 150.00,
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

    public function test_it_rolls_back_transaction_when_detail_creation_fails(): void
    {
        // Arrange - Product_id yang terlalu panjang akan menyebabkan error
        $data = [
            [
                'sku' => str_repeat('A', 100), // Terlalu panjang untuk varchar(50)
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

    public function test_it_handles_empty_item_details(): void
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

    public function test_it_can_handle_multiple_item_details(): void
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

    public function test_it_uses_correct_data_structure_for_slicing(): void
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

    public function test_it_commits_transaction_successfully(): void
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

    public function test_it_throws_exception_on_database_error(): void
    {
        // Arrange - Buat PO pertama dengan detail
        $firstData = [
            [
                'sku' => 'SKU001',
                'qty' => 5,
                'amount' => 50.00,
            ],
            [
                'po_number' => 'PO-007',
                'branch_id' => 1,
                'supplier_id' => 'SUP001',
                'order_date' => '2024-01-01',
                'total' => 50.00,
            ],
        ];
        
        // Simpan PO pertama
        $result = PurchaseOrder::addPurchaseOrder($firstData);
        
        // Pastikan PO pertama tersimpan
        $this->assertNotNull($result);
        $this->assertDatabaseHas('purchase_order', ['po_number' => 'PO-007']);

        // Sekarang coba buat duplicate PO dengan composite key yang sama
        $duplicateData = [
            [
                'sku' => 'SKU002',
                'qty' => 10,
                'amount' => 100.00,
            ],
            [
                'po_number' => 'PO-007',      // Same po_number
                'branch_id' => 1,              // Composite key akan fail
                'supplier_id' => 'SUP001',     // Same supplier (composite key)
                'order_date' => '2024-01-02',
                'total' => 100.00,
            ],
        ];

        // Act & Assert
        $exceptionThrown = false;
        try {
            PurchaseOrder::addPurchaseOrder($duplicateData);
        } catch (\Exception $e) {
            $exceptionThrown = true;
        }
        
        $this->assertTrue($exceptionThrown, 'Exception should be thrown for duplicate primary key');
    }

    public function test_it_preserves_data_integrity_during_rollback(): void
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

    public function test_it_handles_single_item_with_header(): void
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