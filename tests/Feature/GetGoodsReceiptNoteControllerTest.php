<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\GoodsReceiptNote;

class GetGoodsReceiptNoteControllerTest extends TestCase
{
    /** @test */
    public function it_returns_goods_receipt_note_when_it_exists()
    {
        // Ambil satu data nyata dari database
        $grn = GoodsReceiptNote::first();

        // Jika tidak ada data, skip test
        if (!$grn) {
            $this->markTestSkipped('Data GRN tidak ditemukan di database.');
        }

        // Panggil endpoint tanpa getJson(), gunakan get() + header JSON
        $response = $this->get(
            "/goods-receipt-note/{$grn->po_number}",
            ['Accept' => 'application/json']
        );

        // Verifikasi bahwa respons 200 dan data sesuai
        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Goods Receipt Note ditemukan.',
                     'data' => [
                         'po_number' => $grn->po_number,
                         'product_id' => $grn->product_id,
                         'delivery_date' => $grn->delivery_date,
                         'delivered_quantity' => $grn->delivered_quantity,
                         'comments' => $grn->comments,
                     ],
                 ]);
    }
}
