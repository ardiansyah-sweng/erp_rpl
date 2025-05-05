<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ItemController extends Controller
{
    public function getItemAll()
    {
        return (new Item)->getItem();
    }

    public function deleteItem($id)
    {
        // Panggil fungsi deleteItemById dari model Item
        $deleted = Item::deleteItemById($id);

        // Redirect kembali ke halaman list dengan pesan sukses atau gagal
        if ($deleted) {
            return redirect()->back()->with('success', 'Item berhasil dihapus!');
        } else {
            return redirect()->back()->with('error', 'Item tidak ditemukan atau gagal dihapus.');
        }
    }

    // Menampilkan fungsi buat PDF
    public function getItemList(Request $request)
{
    $search = $request->input('search');

    // Jika tombol Export PDF diklik
    if ($request->has('export') && $request->input('export') === 'pdf') {
        $items = Item::query()
            ->when($search, function ($query, $search) {
                $query->where('item_name', 'like', "%$search%");
            })
            ->get();

        $pdf = Pdf::loadView('item.report', ['items' => $items]);
        return $pdf->stream('report-item.pdf');
    }

    // Tampilan default halaman item (tanpa PDF)
    $items = Item::getAllItems($search); 
    return view('item.list', compact('items'));
}
}
