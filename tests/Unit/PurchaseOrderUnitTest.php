<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\PurchaseOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PurchaseOrderUnitTest extends TestCase
{
    /**
     *
     *
     * @return void
     */
    public function test_get_purchase_order_by_status()
    {
        $status = 'Rejected';
        $order = PurchaseOrder::getPurchaseOrderByStatus($status);
        if (!$order) {
            $this->markTestSkipped("Data dengan status '$status' tidak ditemukan di database.");
        }
        $this->assertEquals($status, $order->status);
    }
}