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
    $status = 'Draft';
    $orders = PurchaseOrder::getPurchaseOrderByStatus($status);

    if ($orders->isEmpty()) {
        $this->markTestSkipped("Data dengan status '$status' tidak ditemukan di database.");
    }

     dd($orders->toArray());

    $this->assertTrue(
        $orders->pluck('status')->every(fn($s) => $s === $status),
        "Tidak semua data memiliki status '$status'"
    );
}

}