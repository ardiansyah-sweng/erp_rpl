<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GoodsReceiptNote;

class GoodsReceiptNoteController extends Controller
{
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
    public function getGoodsReceiptNote($po_number): JsonResponse
    {
        $grn = GoodsReceiptNote::getGoodsReceiptNote($po_number);

        if (!$grn) {
            return response()->json([
                'success' => false,
                'message' => 'Goods Receipt Note tidak ditemukan.',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Goods Receipt Note ditemukan.',
            'data' => $grn
        ], 200);
    }
}
