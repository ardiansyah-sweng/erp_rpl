<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\GoodsReceiptNote;

class getGoodsReceiptNotesTest extends TestCase
{
    /**
     * Test getGoodsReceiptNotes() returns all rows for a specific po_number
     */
    public function testGetGoodsReceiptNotesByPoNumber()
    {
        $poNumber = 'PO0001'; // Data nyata dari database

        $result = GoodsReceiptNote::getGoodsReceiptNotes($poNumber);

        // Pastikan hasil berupa koleksi (iterable)
        $this->assertIsIterable($result);

        // Pastikan data tidak kosong
        $this->assertNotEmpty($result);

        // Pastikan semua data memiliki po_number yang sama
        foreach ($result as $item) {
            $this->assertEquals($poNumber, $item->po_number);
        }

        // Tambahan info (opsional)
        echo "\nTotal data ditemukan untuk {$poNumber}: " . count($result);
    }
}