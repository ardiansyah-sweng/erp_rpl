<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GoodsReceiptNote;

class GoodsReceiptNoteController extends Controller
{
    public function updateGoodsReceiptNote(Request $request, $po_number)
    {
        // Validasi input
        $validated = $request->validate([
            'receipt_date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        // Panggil method model
        $updated = GoodsReceiptNote::updateGoodsReceiptNote($po_number, $validated);

        if (!$updated) {
            return response()->json([
                'message' => 'Goods Receipt Note not found.'
            ], 404);
        }

        return response()->json([
            'message' => 'Goods Receipt Note updated successfully.',
            'data' => [
                'receipt_date' => $updated->receipt_date,
                'note' => $updated->note,
            ]
        ]);
    }
}
