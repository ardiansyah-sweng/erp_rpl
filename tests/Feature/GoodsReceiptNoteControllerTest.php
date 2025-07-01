<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\GoodsReceiptNote;

class GoodsReceiptNoteControllerTest extends TestCase
{
    public function test_get_goods_receipt_note_success()
    {
        // Ambil satu data GRN secara acak dari tabel goods_receipt_note
        $grn = GoodsReceiptNote::inRandomOrder()->first();

        // Pastikan data tersedia
        $this->assertNotNull($grn, 'Tidak ada data pada tabel goods_receipt_note untuk dilakukan pengujian.');

        // Kirim request ke endpoint controller
        $response = $this->get("/goods-receipt-note/{$grn->po_number}");

        // Verifikasi respons
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Goods Receipt Note ditemukan.',
            'data' => [
                'po_number' => $grn->po_number,
                // tambahkan kolom lain bila ingin verifikasi lebih
            ],
        ]);
    }
}
