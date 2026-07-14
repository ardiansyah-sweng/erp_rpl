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
use App\Constants\Messages;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseOrderController extends Controller
{
    public function getPurchaseOrder()
    {
        $purchaseOrders = PurchaseOrder::getAllPurchaseOrders();
        $totalOrders = PurchaseOrder::countPurchaseOrder();
        $suppliers = Supplier::all();
        return view('purchase_orders.list', compact('purchaseOrders', 'totalOrders', 'suppliers'));
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
        $suppliers = Supplier::all();
        return view('purchase_orders.list', compact('purchaseOrders', 'keyword', 'totalOrders', 'suppliers'));
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
            return redirect()->back()->with('success', Messages::PO_CREATED);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', Messages::PO_CREATE_FAILED . $e->getMessage());
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

    public function exportPurchaseOrderReport(Request $request)
    {
        // 1. Validasi input: supplier_id sekarang 'nullable', export_type wajib
        $request->validate([
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'supplier_id' => 'nullable|string',
            'export_type' => 'required|string|in:pdf,excel,csv',
        ]);

        $startDate  = Carbon::parse($request->start_date)->startOfDay();
        $endDate    = Carbon::parse($request->end_date)->endOfDay();
        $supplierId = $request->supplier_id;
        $exportType = $request->export_type;

        // 2. Logika Query Data
        if ($supplierId) {
            // Jika spesifik 1 supplier
            $supplierModel = new Supplier();
            $supplier      = $supplierModel->getSupplierById($supplierId);
            $supplierName  = $supplier ? $supplier->company_name : 'Supplier';
            $purchaseOrders = PurchaseOrder::getReportBySupplierAndDate($supplierId, $startDate, $endDate);
        } else {
            // Jika "Semua Supplier" dipilih
            $supplier      = null;
            $supplierName  = 'Semua_Supplier';
            // Menggunakan query builder standar Laravel untuk mengambil semua PO di rentang tanggal
            $purchaseOrders = PurchaseOrder::whereBetween('order_date', [$startDate, $endDate])->get();
        }

        // 3. Routing ke Format Ekspor
        if ($exportType === 'pdf') {
            $data = [
                'purchaseOrders' => $purchaseOrders,
                'supplier'       => $supplier,
                'startDate'      => $startDate->format('d-m-Y'),
                'endDate'        => $endDate->format('d-m-Y'),
                'generatedAt'    => Carbon::now()->format('d-m-Y H:i:s')
            ];
            
            $pdf = Pdf::loadView('purchase_orders.pdf_report', $data);
            return $pdf->stream('laporan_PO_' . str_replace(' ', '_', $supplierName) . '.pdf');
        } 
        // MURNI HANYA TERSISA DUA BLOK INI UNTUK EXCEL DAN CSV
        elseif ($exportType === 'excel') {
            return Excel::download(new PurchaseOrderExport($purchaseOrders), 'laporan_PO_' . str_replace(' ', '_', $supplierName) . '.xlsx');
        } 
        elseif ($exportType === 'csv') {
            return Excel::download(new PurchaseOrderExport($purchaseOrders), 'laporan_PO_' . str_replace(' ', '_', $supplierName) . '.csv', \Maatwebsite\Excel\Excel::CSV);
        }
    }
    public function getPurchaseOrderByStatus($status)
    {
        $purchaseOrders = \App\Models\PurchaseOrder::where('status', $status)
                                      ->latest('order_date')
                                      ->paginate(10);

        $totalOrders = PurchaseOrder::where('status', $status)->count();
        $suppliers = Supplier::all();
        return view('purchase_orders.list', compact('purchaseOrders', 'status', 'totalOrders', 'suppliers'));
    }

    public function destroy($po_number)
    {
        $purchaseOrder = PurchaseOrder::find($po_number);

        if (!$purchaseOrder) {
            return redirect()->route('purchase.orders')->with('error', Messages::PO_NOT_FOUND);
        }

        try {
            DB::beginTransaction();

            $purchaseOrder->details()->delete();
            $purchaseOrder->delete();

            DB::commit();

            return redirect()->route('purchase.orders')->with('success', Messages::PO_DELETED);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', Messages::PO_DELETE_FAILED . $e->getMessage());
        }
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
// Method untuk menampilkan form Edit
    public function edit($po_number)
    {

        $purchaseOrder = \App\Models\PurchaseOrder::where('po_number', $po_number)->first();
        
        if (!$purchaseOrder) {
            abort(404, 'Data Purchase Order tidak ditemukan');
        }

        return view('purchase_orders.edit', compact('purchaseOrder'));
    }

    // Method untuk menyimpan perubahan
    public function update(Request $request, $po_number)
    {
        // Validasi data yang boleh diubah (sesuaikan dengan kebutuhanmu)
        $request->validate([
            'status' => 'required|string',
        ]);

        try {
            // Lakukan update status menggunakan Eloquent/Query Builder
            \App\Models\PurchaseOrder::where('po_number', $po_number)->update([
                'status' => $request->status,
            ]);
            
            return redirect()->route('purchase.orders')->with('success', 'Purchase Order berhasil diupdate.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update PO: ' . $e->getMessage());
        }
    }
}
