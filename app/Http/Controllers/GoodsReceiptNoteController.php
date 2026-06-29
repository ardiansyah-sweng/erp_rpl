<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GoodsReceiptNote;
use Illuminate\Http\JsonResponse;

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
    public function updateGoodsReceiptNote(Request $request, $po_number)
    {
        $validated = $request->validate([
            'delivery_date' => 'required|date',
            'comments' => 'nullable|string|max:255',
        ]);

        $note = GoodsReceiptNote::updateGoodsReceiptNote($po_number, $validated);

        if (!$note) {
            return response()->json([
                'message' => 'Goods Receipt Note not found.'
            ], 404);
        }

        return response()->json([
            'message' => 'Goods Receipt Note updated successfully.',
            'data' => [
                'delivery_date' => $note->delivery_date,
                'comments' => $note->comments,
            ]
        ]);
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

    public function storeFromForm(Request $request)
    {
        $validated = $request->validate([
            'po_number' => 'required|string|exists:purchase_order,po_number',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|string',
            'items.*.delivery_date' => 'required|date',
            'items.*.delivered_quantity' => 'required|integer|min:0',
            'items.*.comments' => 'nullable|string',
        ]);

        foreach ($validated['items'] as $item) {
            GoodsReceiptNote::addGoodsReceiptNote([
                'po_number' => $validated['po_number'],
                'product_id' => $item['product_id'],
                'delivery_date' => $item['delivery_date'],
                'delivered_quantity' => $item['delivered_quantity'],
                'comments' => $item['comments'] ?? null,
            ]);
        }

        return redirect()->route('purchase.orders')->with('success', 'Goods Receipt Note berhasil disimpan.');
    }
}
