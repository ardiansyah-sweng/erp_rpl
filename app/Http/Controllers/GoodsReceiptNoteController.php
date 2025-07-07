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

    public function addGoodsReceiptNote(Request $request)
    {
        // Validasi input sesuai kolom pada tabel
        $validated = $request->validate([
            'po_number'          => 'required|string',
            'product_id'         => 'required|string',
            'delivery_date'      => 'required|date',
            'delivered_quantity' => 'required|integer|min:1',
            'comments'           => 'nullable|string',
        ]);

        // Panggil method insert dari model
        $result = GoodsReceiptNote::addGoodsReceiptNote($validated);

        // Return response
        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Goods Receipt Note berhasil ditambahkan',
                'data'    => $result
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan Goods Receipt Note'
            ], 500);
        }
    }
}

