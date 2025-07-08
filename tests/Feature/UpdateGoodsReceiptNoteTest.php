<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class UpdateGoodsReceiptNoteTest extends TestCase
{
    use WithoutMiddleware;

    public function test_update_goods_receipt_note_success()
    {
        $po_number = 'PO0001';

        $payload = [
            'delivery_date' => '2025-06-13',
            'comments' => 'Update dari test'
        ];

        $response = $this->putJson("/goods-receipt-note/{$po_number}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Goods Receipt Note updated successfully.',
                'data' => [
                    'delivery_date' => $payload['delivery_date'],
                    'comments' => $payload['comments'],
                ]
            ]);
    }
}
