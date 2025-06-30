<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\GoodsReceiptNote;

class GoodsReceiptNoteModelTest extends TestCase
{
    public function test_add_goods_receipt_note()
    {
        $uniquePo = 'PO' . uniqid();
        $data = [
            'po_number' => $uniquePo,
            'product_id' => 'P002-saya',
            'delivery_date' => now()->format('Y-m-d'),
            'delivered_quantity' => 15,
            'comments' => 'Testing tambah GRN',
        ];

        $created = GoodsReceiptNote::addGoodsReceiptNote($data);

        $this->assertDatabaseHas('goods_receipt_notes', [
            'po_number' => $uniquePo,
            'product_id' => 'P002-saya',
        ]);
        $this->assertEquals('Testing tambah GRN', $created->comments);
    }

    public function test_update_goods_receipt_note()
    {
        $uniquePo = 'PO' . uniqid();
        $grn = GoodsReceiptNote::create([
            'po_number' => $uniquePo,
            'product_id' => 'P003-TEST',
            'delivery_date' => now()->format('Y-m-d'),
            'delivered_quantity' => 10,
            'comments' => 'Komentar awal',
        ]);

        $updateData = [
            'comments' => 'Komentar sudah diupdate',
            'delivered_quantity' => 20,
        ];

        $updated = GoodsReceiptNote::updateGoodsReceiptNote($uniquePo, $updateData);

        $this->assertNotNull($updated);
        $this->assertEquals('Komentar sudah diupdate', $updated->comments);
        $this->assertEquals(20, $updated->delivered_quantity);

        $this->assertDatabaseHas('goods_receipt_notes', [
            'po_number' => $uniquePo,
            'comments' => 'Komentar sudah diupdate',
        ]);
    }
}
