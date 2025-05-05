<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\MeasurementUnit;

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
    $validated = $request->validate([
        'product_id'        => 'required|string|size:4',    // ID Produk 4 karakter
        'sku'               => 'required|string',
        'item_name'         => 'required|string|min:3',
        'measurement_unit'  => 'required|string',
        'selling_price'     => 'required|numeric|min:0',
    ]);

    $item = new Item();
    $item->addItem([
        'product_id'        => $validated['product_id'],
        'sku'               => $validated['sku'],
        'item_name'         => $validated['item_name'],
        'measurement_unit'  => $validated['measurement_unit'],
        'selling_price'     => $validated['selling_price'],
    ]);

    return redirect()
        ->route('item.list')
        ->with('success', 'Item berhasil ditambahkan!');
}

public function showAddForm()
{
    $units = MeasurementUnit::all(); // Ambil semua unit
    return view('item.add', compact('units'));
}



    public function getItemList(Request $request)
{
    $search = $request->input('search');
    $items = Item::getAllItems($search);
    return view('item.list', compact('items'));
}


    
}
