<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\GoodsReceiptNote;


class GoodsReceiptNoteController extends Controller
{
    public function updateGoodsReceiptNote(Request $request, string $po_number): JsonResponse
    {
        try {
            // Validasi input berdasarkan kolom di config
            $validatedData = $request->validate([
                'product_id'          => 'sometimes|string|max:255',
                'delivery_date'       => 'sometimes|date',
                'delivered_quantity'  => 'sometimes|numeric|min:0',
                'comments'            => 'sometimes|string|max:1000',
            ]);

            $updated = GoodsReceiptNote::updateGoodsReceiptNote($po_number, $validatedData);

            if (!$updated) {
                return response()->json([
                    'message' => 'Goods Receipt Note not found.'
                ], 404);
            }

            return response()->json([
                'message' => 'Goods Receipt Note updated successfully.',
                'data' => $updated
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update Goods Receipt Note.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
