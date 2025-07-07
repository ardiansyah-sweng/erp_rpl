<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\PurchaseOrderEmail;



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
    public function getPOLength($poNumber, $orderDate) 
    {
        return PurchaseOrder::getPOLength($poNumber, $orderDate);
    }

    public function sendPurchaseOrderEmail($po_number)
    {
        try {
            $poData = PurchaseOrder::with(['details', 'supplier'])
                ->where('po_number', $po_number)
                ->first();
    
            if (!$poData) {
                return redirect()->back()->with('error', "Purchase Order dengan nomor $po_number tidak ditemukan.");
            }
    
            // Hitung subtotal
            $subtotal = $poData->details->sum(function ($item) {
                return $item->quantity * $item->amount;
            });
    
            $tax = $subtotal * 0.1;
    
            $formData = [
                'po_number'     => $poData->po_number,
                'branch'        => $poData->branch_name ?? $poData->branch_id ?? 'Unknown Branch',
                'supplier_id'   => $poData->supplier_id ?? 'Unknown Supplier ID',
                'supplier_name' => $poData->supplier->company_name ?? 'Unknown Supplier',
                'items'         => $poData->details->map(function ($item) {
                    return [
                        'product_id' => $item->product_id,
                        'quantity'   => $item->quantity,
                        'amount'     => $item->amount,
                    ];
                })->toArray(),
                'subtotal' => $subtotal,
                'tax'      => $tax,
            ];
    
            // Kirim email ke dummy email (cek di mailtrap)
            Mail::to('tes@dummy.com')->send(new PurchaseOrderEmail($formData));



    
            return redirect()->back()->with('success', 'Email Purchase Order berhasil dikirim.');
    
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }

}
