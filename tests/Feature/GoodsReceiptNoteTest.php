<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\GoodsReceiptNote;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GoodsReceiptNoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_goods_receipt_note_success()
    {
        $table = config('db_constants.table.grn');
        $columns = config('db_constants.column.grn');

        // Insert data awal
        GoodsReceiptNote::create([
            $columns['po_number'] => 'PO0001',
            $columns['product_id'] => 'PO0001',
            $columns['date'] => now(),
            $columns['qty'] => 100,
            $columns['comments'] => 'Initial delivery',
        ]);

        // Update data
        $updateData = [
            $columns['qty'] => 150,
            $columns['comments'] => 'Updated delivery',
        ];

        $response = $this->putJson("/goods-receipt-notes/PO0001", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Goods Receipt Note updated successfully.',
                'data' => [
                    $columns['qty'] => 150,
                    $columns['comments'] => 'Updated delivery',
                ]
            ]);
    }
}
