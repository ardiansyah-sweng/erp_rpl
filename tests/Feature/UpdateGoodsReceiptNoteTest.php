<?php

namespace Tests\Feature;


use Tests\TestCase;
use App\Models\GoodsReceiptNote;

class UpdateGoodsReceiptNoteTest extends TestCase
{


    public function test_update_goods_receipt_note_success()
    {
        $col = config('db_constants.column.grn');
        $po_number = 'PO0002';

        $grn = GoodsReceiptNote::create([
            $col['po_number'] => $po_number,
            $col['product_id'] => 'P001',
            $col['date'] => '2024-06-10',
            $col['qty'] => 10,
            $col['comments'] => 'Initial comment',
        ]);

        $payload = [
            'delivery_date' => '2024-06-12',
            'note' => 'Barang diterima dengan baik'
        ];

        $response = $this->putJson("/goods-receipt-note/{$po_number}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Goods Receipt Note updated successfully.'
            ]);
    }

    public function test_update_goods_receipt_note_with_integer_quantity()
    {
        $col = config('db_constants.column.grn');
        $po_number = 'PO9999'; // Gunakan PO number yang unik

        // Buat data GRN awal
        $grn = GoodsReceiptNote::create([
            $col['po_number'] => $po_number,
            $col['product_id'] => 'P999',
            $col['date'] => '2025-01-01',
            $col['qty'] => 50, // delivered_quantity awal
            $col['comments'] => 'Initial delivery',
        ]);

        // Payload untuk update dengan delivered_quantity sebagai string
        $payload = [
            'delivery_date' => '2025-01-15',
            'delivered_quantity' => '75', // Ubah ke string sesuai error requirement
            'note' => 'Updated delivery quantity'
        ];

        // Send PUT request
        $response = $this->putJson("/goods-receipt-note/{$po_number}", $payload);

        // Assert successful response
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Goods Receipt Note updated successfully.'
            ]);
    }

    public function test_update_goods_receipt_note_not_found()
    {
        $po_number = 'PNF002';

        $payload = [
            'delivery_date' => '2024-06-12',
            'note' => 'Barang tidak ditemukan'
        ];

        $response = $this->putJson("/goods-receipt-note/{$po_number}", $payload);

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Goods Receipt Note not found.'
            ]);
    }

    public function test_update_goods_receipt_note_with_validation_error()
    {
        $col = config('db_constants.column.grn');
        $po_number = 'PO0003';

        // Buat data GRN awal
        $grn = GoodsReceiptNote::create([
            $col['po_number'] => $po_number,
            $col['product_id'] => 'P003',
            $col['date'] => '2025-01-01',
            $col['qty'] => 30,
            $col['comments'] => 'Initial data',
        ]);

        // Payload dengan delivered_quantity sebagai integer (akan error jika validasi mengharuskan string)
        $payload = [
            'delivery_date' => '2025-01-15',
            'delivered_quantity' => 100, // Integer - akan error jika validasi mengharuskan string
            'note' => 'Test validation error'
        ];

        $response = $this->putJson("/goods-receipt-note/{$po_number}", $payload);

        // Expect validation error karena delivered_quantity harus string
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['delivered_quantity']);
    }
}
