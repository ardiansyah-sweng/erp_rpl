<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\GoodsReceiptNote;

class PurchaseOrderTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        
        config(['db_constants.table.po' => 'purchase_order']);
        config(['db_constants.column.po' => [
            'po_number' => 'po_number',
            'supplier_id' => 'supplier_id',
            'order_date' => 'order_date',
            'total' => 'total',
            'branch_id' => 'branch_id'
        ]]);
    }

    /** @test */
    public function it_returns_empty_array_when_no_po_details_exist()
    {
        $result = PurchaseOrder::getPendingDeliveryQuantity('PO9999');

        $this->assertEquals([], $result);
    }

    /** @test */
    public function it_returns_pending_deliveries_when_no_goods_received()
    {
        PurchaseOrderDetail::create([
            'po_number' => 'PO0001',
            'product_id' => 'PROD001',
            'quantity' => 100,
            'amount' => 50000
        ]);

        PurchaseOrderDetail::create([
            'po_number' => 'PO0001',
            'product_id' => 'PROD002',
            'quantity' => 50,
            'amount' => 25000
        ]);

        $result = PurchaseOrder::getPendingDeliveryQuantity('PO0001');

        $this->assertCount(2, $result);
        
        $this->assertEquals([
            'product_id' => 'PROD001',
            'ordered_qty' => 100,
            'received_qty' => 0,
            'pending_qty' => 100
        ], $result[0]);

        $this->assertEquals([
            'product_id' => 'PROD002',
            'ordered_qty' => 50,
            'received_qty' => 0,
            'pending_qty' => 50
        ], $result[1]);
    }

    /** @test */
    public function it_returns_pending_deliveries_when_partially_received()
    {
        PurchaseOrderDetail::create([
            'po_number' => 'PO0002',
            'product_id' => 'PROD003',
            'quantity' => 100,
            'amount' => 50000
        ]);

        GoodsReceiptNote::create([
            'po_number' => 'PO0002',
            'product_id' => 'PROD003',
            'delivery_date' => now(),
            'delivered_quantity' => 30,
            'comments' => 'Pengiriman pertama'
        ]);

        GoodsReceiptNote::create([
            'po_number' => 'PO0002',
            'product_id' => 'PROD003',
            'delivery_date' => now(),
            'delivered_quantity' => 20,
            'comments' => 'Pengiriman kedua'
        ]);

        $result = PurchaseOrder::getPendingDeliveryQuantity('PO0002');

        $this->assertCount(1, $result);
        $this->assertEquals([
            'product_id' => 'PROD003',
            'ordered_qty' => 100,
            'received_qty' => 50, 
            'pending_qty' => 50   
        ], $result[0]);
    }

    /** @test */
    public function it_returns_empty_array_when_all_items_fully_delivered()
    {
        PurchaseOrderDetail::create([
            'po_number' => 'PO0003',
            'product_id' => 'PROD004',
            'quantity' => 75,
            'amount' => 37500
        ]);

        GoodsReceiptNote::create([
            'po_number' => 'PO0003',
            'product_id' => 'PROD004',
            'delivery_date' => now(),
            'delivered_quantity' => 75,
            'comments' => 'Pengiriman lengkap'
        ]);

        $result = PurchaseOrder::getPendingDeliveryQuantity('PO0003');

        $this->assertEquals([], $result);
    }

    /** @test */
    public function it_handles_over_delivery_scenario()
    {
        PurchaseOrderDetail::create([
            'po_number' => 'PO0004',
            'product_id' => 'PROD005',
            'quantity' => 50,
            'amount' => 25000
        ]);

        GoodsReceiptNote::create([
            'po_number' => 'PO0004',
            'product_id' => 'PROD005',
            'delivery_date' => now(),
            'delivered_quantity' => 60,
            'comments' => 'Pengiriman berlebih'
        ]);

        $result = PurchaseOrder::getPendingDeliveryQuantity('PO0004');
        $this->assertEquals([], $result);
    }

    /** @test */
    public function it_handles_multiple_products_with_mixed_delivery_status()
    {
        PurchaseOrderDetail::create([
            'po_number' => 'PO0005',
            'product_id' => 'PROD006',
            'quantity' => 100,
            'amount' => 50000
        ]);

        PurchaseOrderDetail::create([
            'po_number' => 'PO0005',
            'product_id' => 'PROD007',
            'quantity' => 80,
            'amount' => 40000
        ]);

        PurchaseOrderDetail::create([
            'po_number' => 'PO0005',
            'product_id' => 'PROD008',
            'quantity' => 60,
            'amount' => 30000
        ]);

        GoodsReceiptNote::create([
            'po_number' => 'PO0005',
            'product_id' => 'PROD006',
            'delivery_date' => now(),
            'delivered_quantity' => 40,
            'comments' => 'Sebagian'
        ]);

        GoodsReceiptNote::create([
            'po_number' => 'PO0005',
            'product_id' => 'PROD007',
            'delivery_date' => now(),
            'delivered_quantity' => 80,
            'comments' => 'Lengkap'
        ]);

        $result = PurchaseOrder::getPendingDeliveryQuantity('PO0005');

        $this->assertCount(2, $result); 

        $prod006Result = collect($result)->firstWhere('product_id', 'PROD006');
        $this->assertEquals([
            'product_id' => 'PROD006',
            'ordered_qty' => 100,
            'received_qty' => 40,
            'pending_qty' => 60
        ], $prod006Result);

        $prod008Result = collect($result)->firstWhere('product_id', 'PROD008');
        $this->assertEquals([
            'product_id' => 'PROD008',
            'ordered_qty' => 60,
            'received_qty' => 0,
            'pending_qty' => 60
        ], $prod008Result);
    }

    /** @test */
    public function it_handles_multiple_grn_entries_for_same_product()
    {
        PurchaseOrderDetail::create([
            'po_number' => 'PO0006',
            'product_id' => 'PROD009',
            'quantity' => 200,
            'amount' => 100000
        ]);

        GoodsReceiptNote::create([
            'po_number' => 'PO0006',
            'product_id' => 'PROD009',
            'delivery_date' => now()->subDays(5),
            'delivered_quantity' => 50,
            'comments' => 'Pengiriman 1'
        ]);

        GoodsReceiptNote::create([
            'po_number' => 'PO0006',
            'product_id' => 'PROD009',
            'delivery_date' => now()->subDays(3),
            'delivered_quantity' => 70,
            'comments' => 'Pengiriman 2'
        ]);

        GoodsReceiptNote::create([
            'po_number' => 'PO0006',
            'product_id' => 'PROD009',
            'delivery_date' => now()->subDays(1),
            'delivered_quantity' => 30,
            'comments' => 'Pengiriman 3'
        ]);

        $result = PurchaseOrder::getPendingDeliveryQuantity('PO0006');

        $this->assertCount(1, $result);
        $this->assertEquals([
            'product_id' => 'PROD009',
            'ordered_qty' => 200,
            'received_qty' => 150, // 50 + 70 + 30
            'pending_qty' => 50    // 200 - 150
        ], $result[0]);
    }
}