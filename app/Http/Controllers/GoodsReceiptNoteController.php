<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GoodsReceiptNote;

class GoodsReceiptNoteController extends Controller
{
    public function updateGoodsReceiptNote(Request $request, $id) //andika
    {
        $request->validate([
            'delivery_date' => 'required|date',
            'delivered_quantity' => 'required|numeric|min:1',
            'comments' => 'nullable|string'
        ]);

        // Update data supplier nama perusahaan, alamat, nomor telepon dan akun bank
        $updatedgrn = GoodsReceiptNote::updateGoodsReceiptNote($id, $request->only(['delivery_date', 'delivered_quantity', 'comments'])); //Sudah sesuai pada ERP RPL

        return $updatedgrn;
    }
}
