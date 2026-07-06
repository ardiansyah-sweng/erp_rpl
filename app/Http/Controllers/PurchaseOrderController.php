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
use App\Mail\PurchaseOrderMail;

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
    public function duplicatePurchaseOrder($id)
    {
        try {
            // 1. Dekripsi nomor PO
            $poNumber = \App\Helpers\EncryptionHelper::decrypt($id);

            // 2. Ambil data asli langsung dari MySQL (Bypass Eloquent Model)
            $originalPo = \Illuminate\Support\Facades\DB::table('purchase_order')->where('po_number', $poNumber)->first();

            if (!$originalPo) {
                return redirect()->back()->with('error', 'Data tidak ditemukan di database.');
            }

            // 3. Buat Nomor PO Baru
            $newPoNumber = 'PO' . rand(1000, 9999);

            // 4. Insert data PO baru secara EKSPLISIT ke database
            \Illuminate\Support\Facades\DB::table('purchase_order')->insert([
                'po_number'   => $newPoNumber,
                'supplier_id' => $originalPo->supplier_id,
                'branch_id'   => $originalPo->branch_id,
                'total'       => $originalPo->total,
                'order_date'  => now()->format('Y-m-d'),
                'status'      => 'Draft',
                'created_at'  => now(),
                'updated_at'  => now()
            ]);

            // 5. Tarik dan duplikat isi keranjang barang (Jika ada)
            $originalDetails = \Illuminate\Support\Facades\DB::table('purchase_order_detail')->where('po_number', $poNumber)->get();

            if ($originalDetails->isNotEmpty()) {
                $newDetails = [];
                foreach ($originalDetails as $detail) {
                    $arr = (array) $detail;
                    unset($arr['id']); // Hapus ID lama agar database membuat urutan baru otomatis
                    $arr['po_number'] = $newPoNumber; // Tempelkan barang ke PO yang baru
                    
                    if (isset($arr['created_at'])) $arr['created_at'] = now();
                    if (isset($arr['updated_at'])) $arr['updated_at'] = now();
                    
                    $newDetails[] = $arr;
                }
                // Simpan barang-barang duplikat
                \Illuminate\Support\Facades\DB::table('purchase_order_detail')->insert($newDetails);
            }

            return redirect()->route('purchase.orders')->with('success', 'Purchase Order ' . $poNumber . ' berhasil diduplikasi menjadi ' . $newPoNumber);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menduplikasi Purchase Order: ' . $e->getMessage());
        }
    }
    
}

