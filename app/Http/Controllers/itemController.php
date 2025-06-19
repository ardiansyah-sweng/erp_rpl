<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\MeasurementUnit;
use Barryvdh\DomPDF\Facade\Pdf;
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

    public function updateItem(Request $request, $id)
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'sku' => 'required|string|max:50',
            'item_name' => 'required|string|max:100',
        ]);

         $item = Item::updateItem($id, $validated);

        if (!$item) {
            return redirect()->back()->with('error', 'Item tidak ditemukan.');
        }

        return redirect()->back()->with('success', 'Item berhasil diperbarui.');
    } 
    public function getItemById($id){
        $item = (new item())->getItemById($id);
        return response()->json($item);
    }
    public function printPDFByProductId($productId)
    {
        // TODO: ambil data item berdasarkan productId dari database
        $items = []; // placeholder data

        // TODO: jika data kosong, bisa tambahkan validasi di sini nanti

        // Cetak PDF (view dan data akan diisi nanti)
        return PDF::loadView('item.pdf_by_product', [
                'items' => $items,
                'productId' => $productId
            ])
            ->setPaper('A4', 'portrait')
            ->download("daftar_item_product_{$productId}.pdf");
    }

}
