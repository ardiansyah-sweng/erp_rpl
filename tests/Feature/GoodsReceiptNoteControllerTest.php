<?php

namespace Tests\Feature;
use Tests\TestCase;
use App\Models\GoodsReceiptNote;
use PHPUnit\Framework\Attributes\Test;

class GetGoodsReceiptNoteControllerTest extends TestCase
{
    #[Test]
    public function it_can_add_goods_receipt_note()
    {
        $payload = [
            'po_number'          => 'PO' . rand(100, 999), // max 6 chars, misal: PO123
            'product_id'         => 'P' . rand(100, 999),   // max 6 chars, misal: P456
            'delivery_date'      => now()->toDateString(),
            'delivered_quantity' => 10,
            'comments'           => 'Test entry from feature test'
        ];

        $response = $this->postJson('/goods-receipt-note', $payload);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Goods Receipt Note berhasil ditambahkan',
                     'data'    => [
                         'po_number' => $payload['po_number'],
                         'product_id' => $payload['product_id'],
                         'delivered_quantity' => 10
                     ]
                 ]);
    }

    #[Test]
    public function it_fails_validation_when_required_fields_are_missing()
    {
        $response = $this->postJson('/goods-receipt-note', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'po_number',
            'product_id',
            'delivery_date',
            'delivered_quantity'
        ]);
    }
    
}
