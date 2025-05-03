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
        try {
            // Panggil fungsi deleteItemById dari model Item
            $deleted = Item::deleteItemById($id);
    
            if ($deleted) {
                return redirect()->back()->with('success', 'Item berhasil dihapus!');
            } else {
                return redirect()->back()->with('error', 'Item tidak ditemukan atau gagal dihapus.');
            }
        } catch (\Exception $e) {
            // Tangkap pesan exception dari model
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function getItemList(Request $request)
{
    $search = $request->input('search');
    $items = Item::getAllItems($search);
    return view('item.list', compact('items'));
}
    
}
