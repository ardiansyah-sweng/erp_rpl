<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\GoodsReceiptNote;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateReceiptNoteTest extends TestCase
{

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_update_goods_receipt_note()
    {
        $grn = GoodsReceiptNote::where('po_number', 'PO0001')->first();
        $this->assertNotNull($grn, 'Data dengan po_number P0001 tidak ditemukan.');

        // update via method static
        $updatedGrn = GoodsReceiptNote::updateGoodsReceiptNote('PO0001', [
            'delivered_quantity' => 50,
            'comments' => 'Updated comment',
        ]);

        $this->assertNotNull($updatedGrn);
        $this->assertEquals(50, $updatedGrn->delivered_quantity);
        $this->assertEquals('Updated comment', $updatedGrn->comments);

        // cek juga database ada data yang diupdate
        $this->assertDatabaseHas('goods_receipt_note', [
            'po_number' => 'PO0001',
            'delivered_quantity' => 50,
            'comments' => 'Updated comment',
        ]);
    }
}
