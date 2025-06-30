<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\GoodsReceiptNote;


class GoodsReceiptNoteController extends Controller
{
    public function updateGoodsReceiptNote(Request $request, $po_number)
    {
        // Validasi input (misal: tanggal dan catatan, sesuaikan kebutuhan)
        $validated = $request->validate([
            'receipt_date' => 'required|date',
            'note' => 'nullable|string',
            // tambahkan field lain sesuai kebutuhan
        ]);

        // Cari data berdasarkan po_number
        $goodsReceiptNote = \App\Models\GoodsReceiptNote::where('po_number', $po_number)->first();

        if (!$goodsReceiptNote) {
            // Data tidak ditemukan
            return response()->json([
                'message' => 'Goods Receipt Note not found.'
            ], 404);
        }

        // Update data
        $goodsReceiptNote->update([
            config('db_constants.column.grn.date') => $validated['receipt_date'],
            config('db_constants.column.grn.comments') => $validated['note'],
        ]);

        return response()->json([
            'message' => 'Goods Receipt Note updated successfully.',
            'data' => [
                'receipt_date' => $goodsReceiptNote->fresh()->receipt_date,
                'note' => $goodsReceiptNote->fresh()->note,
            ]
        ]);
    }
}
