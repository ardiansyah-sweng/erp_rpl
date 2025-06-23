<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GoodsReceiptNote;
use Illuminate\Http\JsonResponse;

class GoodsReceiptNoteController extends Controller
{
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
