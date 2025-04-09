<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;

class PurchaseOrderController extends Controller
{
    // Menampilkan daftar PO
    public function getPurchaseOrder()
    {
        $purchaseOrders = PurchaseOrder::getAllPurchaseOrders();
        return view('purchase_orders.list', compact('purchaseOrders'));
    }

    // Menampilkan detail PO berdasarkan nomor
    public function getPurchaseOrderByID($po_number)
    {
        $purchaseOrder = PurchaseOrder::getPurchaseOrderByID($po_number);
        return response()->json($purchaseOrder);
    }

    // Menambahkan PO baru
    public function addPurchaseOrder(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'supplier_id' => 'required|exists:suppliers,supplier_id',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|string',
            'items.*.item_name' => 'required|string',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        try {
            PurchaseOrder::addPurchaseOrder($request->all());
            return redirect()->back()->with('success', 'Purchase Order berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan PO: ' . $e->getMessage());
        }
    }
}