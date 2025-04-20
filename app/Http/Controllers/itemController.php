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
    public function updateItem(Request $request, $id)
{
    
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
    ]);

    $item = Item::find($id);

    if (!$item) {
        return redirect()->back()->with('error', 'Item tidak ditemukan.');
    }
    $item->name = $validated['name'];
    $item->description = $validated['description'] ?? null;
    $item->price = $validated['price'];
    $item->updated_at = now(); 
    if (!$item->created_at) {
        $item->created_at = now(); 
    }

    $item->save();

    return redirect()->back()->with('success', 'Item berhasil diperbarui.');
}

    
}
