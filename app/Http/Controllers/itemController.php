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
public function getItemByType($productType)
{
    $items = Item::getItemByType($productType);


    return view('item.by_type', compact('items', 'productType'));

    // return response()->json($items);
}


    
}
