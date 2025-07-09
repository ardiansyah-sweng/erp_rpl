<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

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

    public function addItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required|string|size:4', // ID Produk 4 karakter
            'sku' => 'required|string',
            'item_name' => 'required|string|min:3',
            'measurement_unit' => 'required|string',
            'selling_price' => 'required|numeric|min:0',
        ]);

        $item = new Item();
        $item->addItem([
            'product_id' => $request->product_id,
            'sku' => $request->sku,
            'item_name' => $request->item_name,
            'measurement_unit' => $request->measurement_unit, // Perbaikan di sini
            'selling_price' => $request->selling_price, // Perbaikan di sini
        ]);

        return redirect()->route('item.list')->with('success', 'Item berhasil ditambahkan!');
    }

    public function showAddForm()
    {
        $units = MeasurementUnit::all(); // ambil semua unit
        return view('item.add', compact('units'));
    }


    public function getItemList(Request $request)
    {
        $search = $request->input('search');
        $items = Item::getAllItems($search);
        return view('item.list', compact('items'));
    }
    

    public function getItemById($id){
        $item = (new item())->getItemById($id);
        return view('item.detail', compact('item'));
    }

    public function getItemByType($productType)
    {
        $items = Item::getItemByType($productType);
        return response()->json($items);
    }
    
    //search
    public function searchItem($keyword)
    {
    $items = Item::where('item_name', 'like', '%' . $keyword . '%')->paginate(10);

    if ($items->isEmpty()) {
        return redirect()->back()->with('error', 'Tidak ada item yang ditemukan untuk kata kunci: ' . $keyword);
    }

    return view('item.list', compact('items'));
    }

    //cetak pdf
    public function exportByProductTypeToPdf($productType)
    {
        $items = Item::getItemByType($productType);

        if (empty($items) || count($items) === 0) {
            return redirect()->back()->with('error', 'Tidak ada item dengan product type tersebut.');
        }

        $pdf = Pdf::loadView('item.pdf_by_product', [
            'items' => $items,
            'productType' => $productType,
        ])->setPaper('A4', 'portrait');

            return $pdf->stream("Item_berdasarkan_product_type_{$productType}.pdf");
    }



}
