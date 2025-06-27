<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\GoodsReceiptNote;

class GoodsReceiptNoteModelTest extends TestCase
{
    public function test_add_goods_receipt_note()
    {
        $data = [
            'po_number' => 'PO0020',
            'product_id' => 'P002-saya',
            'delivery_date' => now()->format('Y-m-d'),
            'delivered_quantity' => 15,
            'comments' => 'Testing tambah GRN',
        ];

        $created = GoodsReceiptNote::addGoodsReceiptNote($data);

        $this->assertDatabaseHas('goods_receipt_note', [
            'po_number' => 'PO0020',
            'product_id' => 'P002-saya',
        ]);

        $this->assertEquals('Testing tambah GRN', $created->comments);
    }

    public function test_update_goods_receipt_note()
    {
        $grn = GoodsReceiptNote::create([
            'po_number' => 'PO0003',
            'product_id' => 'P003-TEST',
            'delivery_date' => now()->format('Y-m-d'),
            'delivered_quantity' => 10,
            'comments' => 'Komentar awal',
            'created_at' => now(),
        ]);

        $po_number = $grn->po_number;

        $updateData = [
            'comments' => 'Komentar sudah diupdate',
            'delivered_quantity' => 20,
        ];

        $updated = GoodsReceiptNote::updateGoodsReceiptNote($po_number, $updateData);

        $this->assertNotNull($updated);
        $this->assertEquals('Komentar sudah diupdate', $updated->comments);
        $this->assertEquals(20, $updated->delivered_quantity);

        $this->assertDatabaseHas('goods_receipt_note', [
            'po_number' => $po_number,
            'comments' => 'Komentar sudah diupdate',
        ]);
    }
}
