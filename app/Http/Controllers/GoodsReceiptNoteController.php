<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GoodsReceiptNote;

class GoodsReceiptNoteController extends Controller
{
    public function update(Request $request, $po_number)
    {
        // Validasi input
        $validated = $request->validate([
            'delivery_date' => 'required|date',
            'delivered_quantity' => 'nullable|string',
            // tambahkan field lain sesuai kebutuhan
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
                'delivery_date' => $updated->delivery_date,
                'note' => $updated->note,
            ]
        ]);
    }
}
