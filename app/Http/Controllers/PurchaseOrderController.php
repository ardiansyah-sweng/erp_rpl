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

    public function getPurchaseOrderByID($id)
    {
        $purchaseOrder = PurchaseOrder::getPurchaseOrderByID($id);
        return view('purchase_orders.detail', compact('purchaseOrder'));
    }
}