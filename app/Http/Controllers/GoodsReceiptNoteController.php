<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GoodsReceiptNote;

class GoodsReceiptNoteController extends Controller
{
    public function updateGoodsReceiptNote(Request $request, $po_number)
    {
        $validated = $request->validate([
            'receipt_date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        // Mapping ke kolom database
        $updateData = [
            'delivery_date' => $validated['receipt_date'],
            'comments' => $validated['note'],
        ];

        $updated = \App\Models\GoodsReceiptNote::updateGoodsReceiptNote($po_number, $updateData);

        if (!$updated) {
            return response()->json([
                'message' => 'Goods Receipt Note not found.'
            ], 404);
        }

        // Ambil data terbaru dari database
        $fresh = \App\Models\GoodsReceiptNote::getGoodsReceiptNote($po_number);

        return response()->json([
            'message' => 'Goods Receipt Note updated successfully.',
            'data' => [
                'receipt_date' => $fresh->delivery_date,
                'note' => $fresh->comments,
            ]
        ]);
    }
}
