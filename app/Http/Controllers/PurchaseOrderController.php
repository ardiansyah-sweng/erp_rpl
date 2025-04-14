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

    // public function getPurchaseOrderByID($po_number)
    // {
    //     $purchaseOrder = PurchaseOrder::getPurchaseOrderByID($po_number);
    //     // return view('purchase_orders.detail', compact('purchaseOrder'));
    //     return response()->json($purchaseOrder);
    // }

    public function getPurchaseOrderByID($po_number)
    {
        $purchaseOrder = PurchaseOrder::with('supplier', 'details')
            ->where('po_number', $po_number)
            ->firstOrFail();
        
        return view('purchase_orders.detail', compact('purchaseOrder'));
    }
    
    public function searchPurchaseOrder()
    {
        $keyword = request()->input('keyword');
        $purchaseOrders = PurchaseOrder::getPurchaseOrderByKeywords($keyword);
        return view('purchase_orders.list', compact('purchaseOrders', 'keyword'));
    }

}