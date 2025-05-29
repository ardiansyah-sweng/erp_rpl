<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\GoodsReceiptNote;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateReceiptNoteTest extends TestCase
{
    use RefreshDatabase; // supaya migrasi dan database bersih sebelum test

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_update_goods_receipt_note()
    {
        // buat data dummy
        $grn = GoodsReceiptNote::create([
            'po_number' => 'PO9999',
            'product_id' => 'P123-test',
            'delivery_date' => '2025-01-01',
            'delivered_quantity' => 10,
            'comments' => 'Initial comment',
        ]);

        // update via method static
        $updatedGrn = GoodsReceiptNote::updateGoodsReceiptNote('PO9999', [
            'delivered_quantity' => 50,
            'comments' => 'Updated comment',
        ]);

        $this->assertNotNull($updatedGrn);
        $this->assertEquals(50, $updatedGrn->delivered_quantity);
        $this->assertEquals('Updated comment', $updatedGrn->comments);

        // cek juga database ada data yang diupdate
        $this->assertDatabaseHas('goods_receipt_note', [
            'po_number' => 'PO9999',
            'delivered_quantity' => 50,
            'comments' => 'Updated comment',
        ]);
    }
}
