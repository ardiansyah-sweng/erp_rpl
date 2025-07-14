<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\GoodsReceiptNote;

class GoodsReceiptNoteControllerTest extends TestCase
{
    // Jika ingin membersihkan dan reset DB tiap test, bisa aktifkan ini:
    // use RefreshDatabase;

    /** @test */
    public function it_returns_goods_receipt_note_when_it_exists()
    {
        // Asumsikan data sudah ada di database
        $existingGRN = GoodsReceiptNote::first();

        // Jika tidak ada data sama sekali, skip test dengan pesan
        if (!$existingGRN) {
            $this->markTestSkipped('No Goods Receipt Note record found in the database.');
            return;
        }

        // Panggil endpoint
        $response = $this->getJson("/api/goods-receipt-note/{$existingGRN->po_number}");

        // Cek respons
        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Goods Receipt Note ditemukan.',
                     'data' => [
                         'po_number' => $existingGRN->po_number,
                         'product_id' => $existingGRN->product_id,
                         'delivery_date' => $existingGRN->delivery_date,
                         'delivered_quantity' => $existingGRN->delivered_quantity,
                         'comments' => $existingGRN->comments,
                     ],
                 ]);
    }

    // /** @test */
    // public function it_returns_404_when_goods_receipt_note_not_found()
    // {
    //     // Gunakan nomor PO yang dijamin tidak ada
    //     $nonExistingPoNumber = 'PO-NOT-EXIST-' . uniqid();

    //     $response = $this->getJson("/api/goods-receipt-note/{$nonExistingPoNumber}");

    //     $response->assertStatus(404)
    //              ->assertJson([
    //                  'success' => false,
    //                  'message' => 'Goods Receipt Note tidak ditemukan.',
    //                  'data' => null,
    //              ]);
    // }
}
