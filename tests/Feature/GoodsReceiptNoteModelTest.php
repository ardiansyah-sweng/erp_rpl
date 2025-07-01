<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\GoodsReceiptNote;

class GoodsReceiptNoteModelTest extends TestCase
{
    public function test_add_goods_receipt_note()
    {
        $data = [
            'po_number' => 'PO0025',
            'product_id' => 'P004-kamu',
            'delivery_date' => now()->format('Y-m-d'),
            'delivered_quantity' => 15,
            'comments' => 'Testing tambah GRN',
        ];

        $created = GoodsReceiptNote::addGoodsReceiptNote($data);

        $this->assertDatabaseHas('goods_receipt_note', [
            'po_number' => 'PO0025',
            'product_id' => 'P004-kamu',
        ]);

        $this->assertEquals('Testing tambah GRN', $created->comments);
    }

}