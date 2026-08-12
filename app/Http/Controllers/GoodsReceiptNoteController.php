<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GoodsReceiptNote;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class GoodsReceiptNoteController extends Controller
{
    public function addGoodsReceiptNote(Request $request)
    {
        $validated = $request->validate([
            'po_number'          => 'required|string',
            'product_id'         => 'required|string',
            'delivery_date'      => 'required|date',
            'delivered_quantity' => 'required|integer|min:1',
            'comments'           => 'nullable|string',
        ]);

        $poItem = DB::table('purchase_order_details')
                    ->where('po_number', $request->po_number)
                    ->where('product_id', $request->product_id)
                    ->first();

        if (!$poItem) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan dalam Purchase Order ini!'
            ], 422);
        }

        $alreadyReceived = DB::table('goods_receipt_note_details')
                            ->where('po_number', $request->po_number)
                            ->where('product_id', $request->product_id)
                            ->sum('delivered_quantity');

        $remainingQty = $poItem->quantity - $alreadyReceived; 

        if ($request->delivered_quantity > $remainingQty) {
            return response()->json([
                'success' => false,
                'message' => "Jumlah ditolak! Kuantitas melebihi sisa PO. Sisa yang boleh diterima hanya: " . $remainingQty . " item."
            ], 422);
        }

        $result = GoodsReceiptNote::addGoodsReceiptNote($validated);

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
}