<?php

use App\Models\GoodsReceiptNote;


namespace App\Http\Controllers;

use App\Models\GoodsReceiptNote;
use Illuminate\Http\Request;

class GoodsReceiptNoteController extends Controller
{
    public function updateGoodsReceiptNote(Request $request, $po_number)
    {
        $validated = $request->validate([
            'delivery_date' => 'required|date',
            'delivered_qty' => 'required|numeric|min:1',
            'comments' => 'nullable|string'
        ]);

        $updated = GoodsReceiptNote::updateGoodsReceiptNote($po_number, $validated);

        if ($updated) {
            return redirect()->back()->with('success', 'Goods Receipt Note berhasil diperbarui.');
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui data. Pastikan PO Number benar.');
        }
    }
}
