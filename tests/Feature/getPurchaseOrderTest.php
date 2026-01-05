<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\PurchaseOrder;
use Tests\TestCase;

class getPurchaseOrderTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    
    public function test_purchase_order_list_page_loads()
    {
        PurchaseOrder::factory()->count(3)->create();

        $response = $this->get('/purchase-orders');

        $response->assertStatus(200);
        $response->assertViewIs('purchase_orders.list');
        $response->assertViewHas('purchaseOrders');
        $response->assertViewHas('totalOrders', 3);
    }
    
    public function test_purchase_order_by_order_date()
    {
        PurchaseOrder::factory()->create(['order_date' => '2023-01-01']);
        PurchaseOrder::factory()->create(['order_date' => '2023-01-02']);

        $response = $this->get('/purchase-orders?order_date=2023-01-01');

        $response->assertStatus(200);
        $response->assertViewIs('purchase_orders.list');
        $response->assertViewHas('purchaseOrders', function ($purchaseOrders) {
            return $purchaseOrders->count() === 1 &&
                   $purchaseOrders->first()->order_date === '2023-01-01';
        });
    }
    
}
