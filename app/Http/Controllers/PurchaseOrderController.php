<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;

class PurchaseOrderController extends Controller
{
    public function getPurchaseOrder()
    {
        $purchaseOrders = PurchaseOrder::orderBy('created_at', 'desc')->paginate(10);
    
        return view('purchase_orders.list', compact('purchaseOrders'));
    }
}
