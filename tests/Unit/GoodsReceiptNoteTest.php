<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\GoodsReceiptNote;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\Supplier;
use App\Models\Branch;
use Faker\Factory as Faker;

class GoodsReceiptNoteTest extends TestCase
{

    private $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create('id_ID');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_create_single_goods_receipt_note()
    {
        // Arrange - Buat PO test data yang unik untuk memastikan tidak ada konflik (max 6 chars)
        $testPoNumber = 'T' . str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT);
        $testProductId = 'TESTSKU' . rand(100, 999);
        
        // Pastikan ada supplier dan branch untuk PO
        $supplier = Supplier::first();
        $branch = Branch::first();
        
        if (!$supplier || !$branch) {
            $this->markTestSkipped('No Supplier or Branch found. Please run seeder first.');
        }

        // Buat PO untuk testing
        $testPO = PurchaseOrder::create([
            'po_number' => $testPoNumber,
            'supplier_id' => $supplier->supplier_id,
            'total' => 100000,
            'branch_id' => $branch->id,
            'order_date' => now(),
            'status' => 'Approved'
        ]);

        // Buat PO Detail untuk testing
        $testPODetail = PurchaseOrderDetail::create([
            'po_number' => $testPoNumber,
            'product_id' => $testProductId,
            'base_price' => 10000,
            'quantity' => 10,
            'amount' => 100000
        ]);

        // Test data untuk GRN
        $grnData = [
            'po_number' => $testPoNumber,
            'product_id' => $testProductId,
            'delivery_date' => now()->format('Y-m-d'),
            'delivered_quantity' => 5,
            'comments' => 'Test GRN input - barang diterima sebagian'
        ];

        // Act - Create GRN record
        $grn = GoodsReceiptNote::create($grnData);

        // Assert - Verify the GRN was created successfully
        $this->assertInstanceOf(GoodsReceiptNote::class, $grn);
        $this->assertEquals($testPoNumber, $grn->po_number);
        $this->assertEquals($testProductId, $grn->product_id);
        $this->assertEquals(5, $grn->delivered_quantity);
        $this->assertEquals('Test GRN input - barang diterima sebagian', $grn->comments);

        // Verify data exists in database
        $this->assertDatabaseHas('goods_receipt_note', [
            'po_number' => $testPoNumber,
            'product_id' => $testProductId,
            'delivered_quantity' => 5,
            'comments' => 'Test GRN input - barang diterima sebagian'
        ]);

        echo "\n✅ GRN berhasil dibuat dengan PO: {$testPoNumber}\n";
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_create_grn_with_minimum_required_data()
    {
        // Arrange - Buat PO test data kedua untuk test minimal data (quantity = 1) (max 6 chars)
        $testPoNumber = 'M' . str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT);
        $testProductId = 'MINSKU' . rand(100, 999);
        
        // Pastikan ada supplier dan branch untuk PO
        $supplier = Supplier::first();
        $branch = Branch::first();
        
        if (!$supplier || !$branch) {
            $this->markTestSkipped('No Supplier or Branch found. Please run seeder first.');
        }

        // Buat PO untuk testing
        $testPO = PurchaseOrder::create([
            'po_number' => $testPoNumber,
            'supplier_id' => $supplier->supplier_id,
            'total' => 50000,
            'branch_id' => $branch->id,
            'order_date' => now(),
            'status' => 'Approved'
        ]);

        // Buat PO Detail untuk testing
        $testPODetail = PurchaseOrderDetail::create([
            'po_number' => $testPoNumber,
            'product_id' => $testProductId,
            'base_price' => 5000,
            'quantity' => 10,
            'amount' => 50000
        ]);

        // Test data dengan data minimum yang diperlukan (quantity = 1)
        $grnData = [
            'po_number' => $testPoNumber,
            'product_id' => $testProductId,
            'delivery_date' => now()->format('Y-m-d'),
            'delivered_quantity' => 1, // Test input 1 data sesuai permintaan user
            'comments' => null // optional field
        ];

        // Act - Create GRN record
        $grn = GoodsReceiptNote::create($grnData);

        // Assert - Verify the GRN was created successfully
        $this->assertInstanceOf(GoodsReceiptNote::class, $grn);
        $this->assertEquals($testPoNumber, $grn->po_number);
        $this->assertEquals($testProductId, $grn->product_id);
        $this->assertEquals(1, $grn->delivered_quantity);
        $this->assertNull($grn->comments);

        // Verify data exists in database
        $this->assertDatabaseHas('goods_receipt_note', [
            'po_number' => $testPoNumber,
            'product_id' => $testProductId,
            'delivered_quantity' => 1
        ]);

        echo "\n✅ GRN minimal data berhasil dibuat dengan PO: {$testPoNumber} (Quantity: 1)\n";
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_retrieve_grn_by_po_number()
    {
        // Arrange - Buat PO dan GRN test data untuk test retrieval (max 6 chars)
        $testPoNumber = 'R' . str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT);
        $testProductId = 'RETSKU' . rand(100, 999);
        
        // Pastikan ada supplier dan branch untuk PO
        $supplier = Supplier::first();
        $branch = Branch::first();
        
        if (!$supplier || !$branch) {
            $this->markTestSkipped('No Supplier or Branch found. Please run seeder first.');
        }

        // Buat PO untuk testing
        $testPO = PurchaseOrder::create([
            'po_number' => $testPoNumber,
            'supplier_id' => $supplier->supplier_id,
            'total' => 75000,
            'branch_id' => $branch->id,
            'order_date' => now(),
            'status' => 'Approved'
        ]);

        // Buat PO Detail untuk testing
        $testPODetail = PurchaseOrderDetail::create([
            'po_number' => $testPoNumber,
            'product_id' => $testProductId,
            'base_price' => 7500,
            'quantity' => 10,
            'amount' => 75000
        ]);

        // Buat GRN untuk testing retrieval
        $grnData = [
            'po_number' => $testPoNumber,
            'product_id' => $testProductId,
            'delivery_date' => now()->format('Y-m-d'),
            'delivered_quantity' => 3,
            'comments' => 'Test retrieval functionality'
        ];

        $createdGrn = GoodsReceiptNote::create($grnData);

        // Act - Retrieve GRN using static method
        $retrievedGrn = GoodsReceiptNote::getGoodsReceiptNote($testPoNumber);

        // Assert - Verify the GRN was retrieved successfully
        $this->assertNotNull($retrievedGrn);
        $this->assertEquals($testPoNumber, $retrievedGrn->po_number);
        $this->assertEquals($testProductId, $retrievedGrn->product_id);
        $this->assertEquals(3, $retrievedGrn->delivered_quantity);
        $this->assertEquals('Test retrieval functionality', $retrievedGrn->comments);
        
        echo "\n✅ GRN berhasil di-retrieve untuk PO: {$testPoNumber}\n";
    }
} 