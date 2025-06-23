<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\GoodsReceiptNote;

class PurchaseOrderTest extends TestCase
{
    /** @test */
    public function it_works_with_existing_database_data()
    {
        $existingPO = PurchaseOrder::with('details')->first();
        
        if ($existingPO) {
            $result = PurchaseOrder::getPendingDeliveryQuantity($existingPO->po_number);
            
            $this->assertIsArray($result);
            
            if (!empty($result)) {
                $this->assertArrayHasKey('product_id', $result[0]);
                $this->assertArrayHasKey('ordered_qty', $result[0]);
                $this->assertArrayHasKey('received_qty', $result[0]);
                $this->assertArrayHasKey('pending_qty', $result[0]);
                
                foreach ($result as $delivery) {
                    $this->assertGreaterThan(0, $delivery['pending_qty']);
                }
            }
            
            echo "\nTesting PO: {$existingPO->po_number}\n";
            echo "Pending deliveries found: " . count($result) . "\n";
            
            if (!empty($result)) {
                foreach ($result as $delivery) {
                    echo "Product: {$delivery['product_id']}, Ordered: {$delivery['ordered_qty']}, Received: {$delivery['received_qty']}, Pending: {$delivery['pending_qty']}\n";
                }
            }
        } else {
            $this->markTestSkipped('No existing PO data found in database');
        }
    }

    /** @test */
    public function it_correctly_calculates_with_real_po_data()
    {
        $purchaseOrders = PurchaseOrder::with('details')->take(3)->get();
        
        foreach ($purchaseOrders as $po) {
            $result = PurchaseOrder::getPendingDeliveryQuantity($po->po_number);
            
            $expectedResults = [];
            
            foreach ($po->details as $detail) {
                $receivedQty = GoodsReceiptNote::where('po_number', $po->po_number)
                    ->where('product_id', $detail->product_id)
                    ->sum('delivered_quantity');
                
                $pendingQty = $detail->quantity - $receivedQty;
                
                if ($pendingQty > 0) {
                    $expectedResults[] = [
                        'product_id' => $detail->product_id,
                        'ordered_qty' => $detail->quantity,
                        'received_qty' => $receivedQty,
                        'pending_qty' => $pendingQty
                    ];
                }
            }
            
            $this->assertEquals(count($expectedResults), count($result), 
                "PO {$po->po_number}: Jumlah pending deliveries tidak sesuai");
            
            foreach ($expectedResults as $expected) {
                $actual = collect($result)->firstWhere('product_id', $expected['product_id']);
                $this->assertNotNull($actual, 
                    "PO {$po->po_number}: Product {$expected['product_id']} tidak ditemukan dalam hasil");
                $this->assertEquals($expected, $actual, 
                    "PO {$po->po_number}: Data pending delivery untuk {$expected['product_id']} tidak sesuai");
            }
            
            echo "\nPO: {$po->po_number} - Pending deliveries: " . count($result) . "\n";
        }
    }
}