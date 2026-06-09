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

// Menampilkan halaman edit PO
public function edit($id)
    {
        $decryptedId = \App\Helpers\EncryptionHelper::decrypt($id);
        $purchaseOrder = PurchaseOrder::getPurchaseOrderByID($decryptedId);

        if (!$purchaseOrder) {
            abort(404, 'Data Purchase Order tidak ditemukan');
        }

        $detailTable = config('db_constants.table.po_detail') ?? 'purchase_order_detail';
        
        $items = \Illuminate\Support\Facades\DB::table($detailTable)
                    ->leftJoin('items', 'purchase_order_detail.product_id', '=', 'items.sku') 
                    ->where('purchase_order_detail.po_number', $decryptedId)
                    ->select(
                        'purchase_order_detail.*', 
                        'items.name as item_name'
                        // BARIS HARGA DIHAPUS
                    )
                    ->get();

        $purchaseOrder->items = $items;
        $suppliers = Supplier::all();

        return view('purchase_orders.edit', compact('purchaseOrder', 'suppliers'));
    }
// Memproses data update PO
    public function updatePurchaseOrder(Request $request, $id)
    {
        // 1. Dekripsi ID dari URL
        $decryptedId = \App\Helpers\EncryptionHelper::decrypt($id);

        try {
            DB::beginTransaction();

            $poTable = (new \App\Models\PurchaseOrder())->getTable();

            // 2. PERBAIKAN UTAMA: Cari data PO asli di database untuk mengambil po_number yang valid
            $poData = DB::table($poTable)
                        ->where('po_number', $decryptedId)
                        ->first();

            if (!$poData) {
                throw new \Exception("Data Purchase Order tidak ditemukan di database.");
            }

            // Ini adalah Nomor PO asli berupa string yang akan disetujui oleh database
            $realPoNumber = $poData->po_number; 

            // 3. Membersihkan format titik dan koma pada Subtotal
            $totalHarga = str_replace('.', '', $request->input('subtotal'));
            $totalHarga = str_replace(',', '', $totalHarga); 
            
            // 4. Update Data Master PO
            DB::table($poTable)->where('po_number', $realPoNumber)->update([
                'branch_id'   => $request->input('branch_id'),
                'supplier_id' => $request->input('supplier_id'),
                'total'       => (int) $totalHarga,
            ]);

            // 5. Reset Data Detail Lama
            $detailTable = config('db_constants.table.po_detail') ?? 'purchase_order_detail';
            DB::table($detailTable)->where('po_number', $realPoNumber)->delete();

            // 6. Insert Data Detail Baru
            $skus   = $request->input('sku');
            $qtys   = $request->input('qty');
            $prices = $request->input('unit_price');

            if ($skus && is_array($skus)) {
                $detailBarang = [];
                $cekDuplikatId = []; // TAMBAHAN: Array untuk melacak ID barang agar tidak dobel

                for ($i = 0; $i < count($skus); $i++) {
                    if (!empty($skus[$i])) {
                        
                        $inputValue = $skus[$i];
                        $itemData = DB::table('items')
                                      ->where('sku', $inputValue)
                                      ->orWhere('id', $inputValue)
                                      ->first();

                        $realProductId = $itemData ? $itemData->id : $inputValue;

                        // --- FILTER ANTI DUPLIKAT ---
                        // Jika ID barang sudah pernah dimasukkan sebelumnya, lewati agar tidak error 1062
                        if (in_array($realProductId, $cekDuplikatId)) {
                            continue; 
                        }
                        $cekDuplikatId[] = $realProductId; // Catat ID bahwa barang ini sudah aman
                        // ----------------------------

                        $detailBarang[] = [
                            'po_number'     => $realPoNumber, 
                            'product_id'    => $realProductId,
                            'base_price'    => $prices[$i] ?? 0,
                            'quantity'      => $qtys[$i] ?? 1,
                            'amount'        => ($qtys[$i] ?? 1) * ($prices[$i] ?? 0), 
                            'received_days' => 0,
                            'created_at'    => now(),
                            'updated_at'    => now(),
                        ];
                    }
                }

                // Matikan pengecekan FK sementara
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                
                // Simpan Data yang sudah bersih dari duplikat
                DB::table($detailTable)->insert($detailBarang);
                
                // Nyalakan kembali
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }

            DB::commit();
            return redirect()->route('purchase.orders')->with('success', 'Purchase Order berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Pengaman: Pastikan aturan DB kembali normal meskipun terjadi error
            DB::statement('SET FOREIGN_KEY_CHECKS=1;'); 
            
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }
}
