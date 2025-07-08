<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GoodsReceiptNote;

class GoodsReceiptNoteController extends Controller
{
    public function updateGoodsReceiptNote(Request $request, $po_number)
    {
        $validated = $request->validate([
            'delivery_date' => 'required|date',
            'comments' => 'nullable|string|max:255',
        ]);

        $note = GoodsReceiptNote::where('po_number', $po_number)->first();

        if (!$note) {
            return response()->json([
                'message' => 'Goods Receipt Note not found.'
            ], 404);
        }

        $note->update($validated);

        return response()->json([
            'message' => 'Goods Receipt Note updated successfully.',
            'data' => [
                'delivery_date' => $note->delivery_date,
                'comments' => $note->comments,
            ]
        ]);
    }
}
