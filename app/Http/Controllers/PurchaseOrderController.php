<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Validator;
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

    public function sendPurchaseOrderEmail($po_number)
{
    try {
        $purchaseOrder = PurchaseOrder::getPurchaseOrderByID($po_number);

        if (!$purchaseOrder || $purchaseOrder->count() === 0) {
            return "Gagal menampilkan email: Purchase Order dengan nomor $po_number tidak ditemukan.";
        }

        $poData = $purchaseOrder->first();

        $formData = [
            'po_number'      => $poData->po_number,
            'branch'         => $poData->branch_name ?? $poData->branch_id ?? 'Unknown Branch',
            'supplier_id'    => $poData->supplier_id ?? 'Unknown Supplier ID',
            'supplier_name'  => $poData->supplier_name ?? $poData->supplier_id ?? 'Unknown Supplier',
            'items'          => $this->getPoItems($po_number),
            'subtotal'       => $poData->subtotal ?? $poData->total ?? 0,
            'tax'            => $poData->tax ?? 0,
        ];

        return view('purchase_orders.email', compact('formData'));

    } catch (\Exception $e) {
        return "Gagal menampilkan email: " . $e->getMessage();
    }
}


    
    private function getPoItems($po_number)
    {
        try {
            Log::info("Mencoba mengambil item untuk PO: $po_number");
            $items = DB::table('purchase_order_items')
                ->where('po_number', $po_number)
                ->get();
            if ($items->isEmpty()) {
                Log::warning("Item tidak ditemukan di tabel purchase_order_items, mencoba purchase_order_details");
                $items = DB::table('purchase_order_details')
                    ->where('po_number', $po_number)
                    ->get();
            }
            Log::info("Item ditemukan: " . $items->count());
            Log::info("Item data: " . json_encode($items->toArray()));

            $formattedItems = [];
            foreach ($items as $item) {
                $formattedItems[] = [
                    'product_id' => $item->sku ?? $item->product_id ?? $item->item_code ?? 'Unknown Product',
                    'quantity' => $item->qty ?? $item->quantity ?? 0,
                    'amount' => $item->amount ?? $item->price ?? $item->unit_price ?? 0
                ];
            }
            if (empty($formattedItems)) {
                Log::warning("Tidak ada item ditemukan untuk PO: $po_number, menggunakan data contoh");
                $formattedItems = [
                    ['product_id' => 'SAMPLE-001', 'quantity' => 1, 'amount' => 10000]
                ];
            }

            return $formattedItems;
        } catch (\Exception $e) {
            Log::error("Error saat mengambil item PO: " . $e->getMessage());
            return [
                ['product_id' => 'ERROR-ITEM', 'quantity' => 0, 'amount' => 0]
            ];
        }
    }
}
