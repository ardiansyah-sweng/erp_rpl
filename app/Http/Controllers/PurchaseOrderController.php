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

class PurchaseOrderController extends Controller
{
    // Fungsi printPurchaseOrderToPDF sesuai permintaan
    public function printPurchaseOrderToPDF($id)
    {
        // Ambil data purchase order berdasarkan ID
        $purchaseOrder = PurchaseOrder::findOrFail($id);

        // Data tambahan jika perlu
        $data = [
            'purchaseOrder' => $purchaseOrder,
        ];

        // Generate PDF dari view (buat view purchase_orders.pdf_detail jika belum ada)
        $pdf = Pdf::loadView('purchase_orders.pdf_detail', $data);
        return $pdf->stream('purchase_order_' . $purchaseOrder->po_number . '.pdf');
    }
    public function getPurchaseOrder()
    {
        $purchaseOrders = PurchaseOrder::getAllPurchaseOrders();
        $totalOrders = PurchaseOrder::countPurchaseOrder();
        return view('purchase_orders.list', compact('purchaseOrders', 'totalOrders'));
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
        $totalOrders = PurchaseOrder::countPurchaseOrder();
        return view('purchase_orders.list', compact('purchaseOrders', 'keyword', 'totalOrders'));
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

    public function showReportForm()
    {
        $suppliers = Supplier::all(); // dropdown untuk supplier semua
        return view('purchase_orders.report_form', compact('suppliers'));
    }

    public function generatePurchaseOrderPDF(Request $request)
    {
        // Validasi input
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'supplier_id' => 'required|string',
        ]);

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();
        $supplierId = $request->supplier_id;

        // Buat instance Supplier dan panggil getSupplierById
        $supplierModel = new Supplier();
        $supplier = $supplierModel->getSupplierById($supplierId);

        // Ambil data purchase order
        $purchaseOrders = PurchaseOrder::getReportBySupplierAndDate($supplierId, $startDate, $endDate);

        $data = [
            'purchaseOrders' => $purchaseOrders,
            'supplier' => $supplier,
            'startDate' => $startDate->format('d-m-Y'),
            'endDate' => $endDate->format('d-m-Y'),
            'generatedAt' => Carbon::now()->format('d-m-Y H:i:s')
        ];

        $pdf = Pdf::loadView('purchase_orders.pdf_report', $data);
        return $pdf->stream('laporan_purchase_order_' . $supplier->company_name . '.pdf');
    }
    public function getPurchaseOrderByStatus($status)
    {
        $purchaseOrders = \App\Models\PurchaseOrder::where('status', $status)
                                      ->latest('order_date')
                                      ->paginate(10);

        $totalOrders = PurchaseOrder::where('status', $status)->count();
        return view('purchase_orders.list', compact('purchaseOrders', 'status', 'totalOrders'));
    }
    public function sendMailPurchaseOrder(Request $request)
    {
        $data = $request->all();

        if (empty($data['header']) || empty($data['items'])) {
            return response()->json(['error' => 'Data tidak lengkap untuk mengirim email.'], 400);
        }

        try {
            $emailTujuan = 'syah.ykm@gmail.com'; // Ganti dengan email Anda jika perlu
            $dataUntukEmail = ['data' => $data];

            Mail::send('purchase_orders.email', $dataUntukEmail, function ($message) use ($emailTujuan, $data) {
                $message->to($emailTujuan)
                        ->subject('Purchase Order Baru: ' . $data['header']['po_number']);
            });

            return response()->json(['success' => 'Email pesanan berhasil dikirim.']);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Server gagal mengirim email: ' . $e->getMessage()], 500);
        }
    }
}
