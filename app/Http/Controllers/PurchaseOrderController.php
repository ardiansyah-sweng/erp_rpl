<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;

class PurchaseOrderController extends Controller
{
    public function getPurchaseOrder()
    {
        $purchaseOrders = PurchaseOrder::getAllPurchaseOrders();
        return view('purchase_orders.list', compact('purchaseOrders'));
    }
    public function searchPurchaseOrder(Request $request)
    {
        $keywords = $request->input('keywords');
        $purchaseOrders = PurchaseOrder::getPurchaseOrderByKeywords($keywords);
        return view('purchase_orders.list', ['purchaseOrders' => $purchaseOrders]);
    }

    public function getPurchaseOrderByID($po_number)
    {
        $purchaseOrder = PurchaseOrder::getPurchaseOrderByID($po_number);
        // return view('purchase_orders.detail', compact('purchaseOrder'));
        return response()->json($purchaseOrder);
    }

}