<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\GoodsReceiptNote;


class GoodsReceiptNoteControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_goods_receipt_note_success()
    {
        // Simulasi data GRN
        $grn = GoodsReceiptNote::create([
            'po_number' => 'PO12345',
            // tambahkan kolom lain yang sesuai konfigurasi config('db_constants.column.grn')
        ]);

        $response = $this->get("/goods-receipt-note/{$grn->po_number}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Goods Receipt Note ditemukan.',
            'data' => [
                'po_number' => $grn->po_number,
                // tambahkan kolom lain untuk verifikasi bila perlu
            ],
        ]);
    }
}
