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
        } 
        catch (\Exception $e) {
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

    public function getItemById($id)
    {
        $item = [
            1 => [
                'id' => 1,
                'product_id' => 'TTM',
                'sku' => 'TTM-Libero',
                'item_name' => 'TTRA-Libero',
                'measurement_unit' => '21',
                'avg_base_price' => '0',
                'selling_price' => '0',
                'purchase_unit' => '30',
                'sell_unit' => '30',
                'stock_unit' => '30',
                'created_at' => '2025-03-12 19:48:13',
                'updated_at' => '2025-03-12 19:48:13'
            ],
            2 => [
                'id' => 2,
                'product_id' => 'TTE',
                'sku' => 'TTM-Libera',
                'item_name' => 'TTRA-Libera',
                'measurement_unit' => '20',
                'avg_base_price' => '0',
                'selling_price' => '0',
                'purchase_unit' => '21',
                'sell_unit' => '30',
                'stock_unit' => '30',
                'created_at' => '2025-03-12 19:40:13',
                'updated_at' => '2025-03-12 19:48:13'
            ],
            3 => [
                'id' => 3,
                'product_id' => 'TTY',
                'sku' => 'TTM-Libero',
                'item_name' => 'TTRA-Libero',
                'measurement_unit' => '21',
                'avg_base_price' => '0',
                'selling_price' => '0',
                'purchase_unit' => '30',
                'sell_unit' => '30',
                'stock_unit' => '30',
                'created_at' => '2025-03-12 19:48:13',
                'updated_at' => '2025-03-12 19:48:13'
            ]
        ];

        if (!isset($item[$id])) {
            abort(404, 'Product not found.');
        }

        $item = (object) $item[$id];

        return view('item.detail', compact('item'));
    
    public function getItemById($id){
        $item = (new item())->getItemById($id);
        return response()->json($item);
    }
}
