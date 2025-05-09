<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\PurchaseOrderMail;
use Carbon\Carbon;

class PurchaseOrderController extends Controller
{
    public function getPurchaseOrder()
    {
        $purchaseOrders = PurchaseOrder::getAllPurchaseOrders();
        return view('purchase_orders.list', compact('purchaseOrders'));
    }

    public function getPurchaseOrderByID($po_number)
    {
        $purchaseOrder = PurchaseOrder::getPurchaseOrderByID($po_number);
        return view('purchase_orders.detail', compact('purchaseOrder'));
    }
    public function searchPurchaseOrder()
    {
        $keyword = request()->input('keyword');
        $purchaseOrders = PurchaseOrder::getPurchaseOrderByKeywords($keyword);
        return view('purchase_orders.list', compact('purchaseOrders', 'keyword'));
    }

    // Menambahkan PO baru
    public function addPurchaseOrder(Request $request)
    {
        $allData = $request->all();

        // Ambil item detail (0–n-1)
        $itemDetails = array_slice($allData, 0, -1);

        // Ambil header data (elemen terakhir)
        $headerData = end($allData);

        // Validasi item detail
        foreach ($itemDetails as $index => $item) {
            Validator::make($item, [
                'po_number' => 'required|string',
                'sku'       => 'required|string',
                'qty'       => 'required|numeric|min:1',
                'amount'    => 'required|numeric|min:0',
            ])->validate();
        }

        // Validasi header
        Validator::make($headerData, [
            'po_number'   => 'required|string',
            'branch_id'   => 'required|integer',
            'supplier_id' => 'required|string',
            'total'       => 'required|numeric|min:0',
            'order_date'  => 'required|date',
        ])->validate();

        try {
            PurchaseOrder::addPurchaseOrder($allData);
            return redirect()->back()->with('success', 'Purchase Order berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan PO: ' . $e->getMessage());
        }
    }

    public static function getPOLength($poNumber, $orderDate)
    {
        $po = PurchaseOrder::getPurchaseOrderByID($poNumber);
        
        if (!$po || $po->count() === 0) {
            return null;
        }
    
        // Ambil data PO pertama dari hasil paginate
        $poData = $po->first();
        
        $orderDate = Carbon::parse($orderDate);
        $statusUpdateDate = Carbon::parse($poData->updated_at);
    
        return intval($orderDate->diffInDays($statusUpdateDate));
    }
    public function sendMailPurchaseOrder()
    {
        // Ambil semua purchase orders
        $purchaseOrders = PurchaseOrder::all();

        // Mengecek jika tidak ada purchase order
        if ($purchaseOrders->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada Purchase Order untuk dikirim.');
        }

        // Mengirim email ke setiap PO
        foreach ($purchaseOrders as $po) {
            try {
                // Kirim email ke supplier dengan data PO
                Mail::send('emails.purchase_order', ['po' => $po], function ($message) use ($po) {
                    $message->to($po->supplier->email ?? 'default@example.com')
                            ->subject('Purchase Order #' . $po->po_number); // Set subject email
                });
            } catch (\Exception $e) {
                // Jika gagal mengirim email, tampilkan error
                return redirect()->back()->with('error', 'Gagal mengirim email untuk PO ' . $po->po_number . ': ' . $e->getMessage());
            }
        }

        // Setelah semua email berhasil dikirim
        return redirect()->back()->with('success', 'Email Purchase Order berhasil dikirim ke semua supplier.');
    }
}


