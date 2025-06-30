<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\GoodsReceiptNote;

class GoodsReceiptNoteControllerTest extends TestCase
{
    use WithFaker;
    //andika
    /** @test */
    public function test_update_goods_receipt_note_success()
    {
        $col = config('db_constants.column.grn');
        $po_number = 'PO0001';

        // Pastikan data test tidak duplikat
        GoodsReceiptNote::where($col['po_number'], $po_number)->delete();

        $grn = GoodsReceiptNote::create([
            $col['po_number'] => $po_number,
            $col['product_id'] => 'P001',
            $col['date'] => '2024-06-10',
            $col['qty'] => 10,
            $col['comments'] => 'Initial comment',
        ]);

        $payload = [
            'receipt_date' => '2024-06-12',
            'note' => 'Barang diterima dengan baik'
        ];

        $response = $this->patchJson("/goods-receipt-note/{$po_number}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Goods Receipt Note updated successfully.',
                'data' => [
                    'receipt_date' => $payload['receipt_date'],
                    'note' => $payload['note'],
                ]
            ]);

        $this->assertDatabaseHas($grn->getTable(), [
            $col['po_number'] => $po_number,
            $col['date'] => $payload['receipt_date'],
            $col['comments'] => $payload['note'],
        ]);

        // (Opsional) Hapus data test setelah selesai
        GoodsReceiptNote::where($col['po_number'], $po_number)->delete();
    }

    /** @test */
    public function test_update_goods_receipt_note_not_found()
    {
        $po_number = 'PNF001';
        $payload = [
            'receipt_date' => '2024-06-12',
            'note' => 'Barang tidak ditemukan'
        ];

        $response = $this->patchJson("/goods-receipt-note/{$po_number}", $payload);

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Goods Receipt Note not found.'
            ]);
    }
}
