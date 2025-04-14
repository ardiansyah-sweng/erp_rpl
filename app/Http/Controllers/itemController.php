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


    public function getItemList(Request $request)
{
    $search = $request->input('search');
    $items = Item::getAllItems($search);
    return view('item.list', compact('items'));
}
    
    public function addItem(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|min:3|unique:item,item_name',
            'id' => 'required|string|min:3',
            'sku' => 'required|string|min:3',
            'measurement_unit' => 'required|string|min:3',
            'avg_base_price' => 'required|string|min:3',
            'selling_price' => 'required|string|min:3'
            
        ]);


        $item = new Item();
        $item->additem([
            'item_name' => $request->item_name,
            'id' => $request->id,
            'sku' => $request->sku,
            'measurement_unit' => $request->measurement_unit,
            'avg_base_price' => $request->avg_base_price,
            'selling_price' => $request->selling_price

            
        ]);

        return redirect()->route('item.list')->with('success', 'Cabang berhasil ditambahkan!');
    }

    
}
