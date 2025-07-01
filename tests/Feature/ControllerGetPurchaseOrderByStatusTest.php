<?php

namespace Tests\Feature;

use Tests\TestCase;

class ControllerGetPurchaseOrderByStatusTest extends TestCase
{
    /** @test */
    public function it_returns_purchase_orders_with_valid_status()
    {
        $response = $this->get('/purchase-orders/status/pending');

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'pending',
            'count' => 2,
        ]);
        $response->assertJsonStructure([
            'status',
            'count',
            'data' => [
                ['id', 'status', 'supplier', 'total'],
            ]
        ]);
    }

    /** @test */
    public function it_returns_404_when_status_not_found()
    {
        $response = $this->get('/purchase-orders/status/nonexistent');

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'No purchase orders found with status: nonexistent',
        ]);
    }
}
