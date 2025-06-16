<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GoodsReceiptNoteTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_update_goods_receipt_note_success()
    {
        $po_number = 'PO001';
        $updateData = [
            'supplier_name' => 'Updated Supplier',
            'total_quantity' => 150,
            'status' => 'received',
            'notes' => 'Updated successfully'
        ];

        $response = $this->putJson("/goods-receipt-notes/{$po_number}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Goods Receipt Note updated successfully'
            ]);
    }

    /**
     * Test update dengan data tidak valid
     */
    public function test_update_with_invalid_data()
    {
        $po_number = 'PO001';
        $invalidData = [
            'total_quantity' => -5,
            'status' => 'invalid_status'
        ];

        $response = $this->putJson("/goods-receipt-notes/{$po_number}", $invalidData);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation failed'
            ]);
    }

    /**
     * Test update dengan data minimal
     */
    public function test_update_with_minimal_data()
    {
        $po_number = 'PO002';
        $minimalData = [
            'notes' => 'Just updating notes'
        ];

        $response = $this->putJson("/goods-receipt-notes/{$po_number}", $minimalData);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
    }
}
