<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\GoodsReceiptNote;

class UpdateGoodsReceiptNoteTest extends TestCase
{
    use WithoutMiddleware;

    public function test_update_goods_receipt_note_success()
    {
        $col = config('db_constants.column.grn');
        $po_number = 'PO0001';

        $payload = [
            'receipt_date' => '2025-06-13',
            'note' => 'Update dari test'
        ];

        $response = $this->putJson("/goods-receipt-note/{$po_number}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Goods Receipt Note updated successfully.',
                'data' => [
                    'receipt_date' => $payload['receipt_date'],
                    'note' => $payload['note'],
                ]
            ]);
    }
}
