<?php

use App\Models\GoodsReceiptNote;


namespace App\Http\Controllers;

use App\Models\GoodsReceiptNote;
use Illuminate\Http\Request;

class GoodsReceiptNoteController extends Controller
{
    public function update(Request $request, $po_number)
    {
        $data = $request->only(['product_id', 'delivery_date', 'delivered_quantity', 'comments']);

        $grn = GoodsReceiptNote::where('po_number', $po_number)->first();

        if (!$grn) {
            return response()->json(['message' => 'PO Number not found'], 404);
        }

        $grn->update($data);

        return response()->json([
            'message' => 'GRN updated successfully',
            'data' => $grn
        ]);
    }
}
