<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Enums\POStatus;
use Carbon\Carbon;

class PurchaseOrder extends Model
{
    protected $table;
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Tetapkan nama tabel dan kolom
        $this->table = config('db_constants.table.po');
        $this->fillable = array_values(config('db_constants.column.po') ?? []);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    public function details()
    {
        return $this->hasMany(PurchaseOrderDetail::class, 'po_number', 'po_number');
    }

    public static function getAllPurchaseOrders()
    {
        // Mengurutkan supplier berdasarkan tanggal pesanan(order_date) secara Descending
        return self::with('supplier')->orderBy('order_date', 'desc')->paginate(10);
    }

    public static function getPurchaseOrderByKeywords($keywords = null)
    {
        $query = self::with('supplier');

        if ($keywords) {
            $query->where(function ($q) use ($keywords) {
                $q->where('po_number', 'LIKE', "%{$keywords}%")
                    ->orWhere('status', 'LIKE', "%{$keywords}%")
                    ->orWhereHas('supplier', function ($subQuery) use ($keywords) {
                        $subQuery->where('company_name', 'LIKE', "%{$keywords}%");
                    });
            });
        }

        return $query->orderBy('created_at', 'asc')
            ->paginate(10);
    }

    public static function getPurchaseOrderByID($po_number)
    {
        return self::with('supplier', 'details')->orderBy('po_number')->where('po_number', $po_number)->paginate(10);
    }

    // Fungsi tambahan untuk menghitung jumlah item pada 1 PO
    public static function countItem($poNumber)
    {
        return PurchaseOrderDetail::where('po_number', $poNumber)->count();
    }

    public static function countPurchaseOrder()
    {
        return self::count();
    }

    //Menghitung jumlah purchase order by supplier
    public static function countPurchaseOrderBySupplier($supplier_id)
    {
        return self::where('supplier_id', $supplier_id)->count();
    }
    
    /**
     * Fungsi untuk menambahkan Purchase Order baru
     */
    public static function addPurchaseOrder($data)
    {
        DB::beginTransaction();
        
        // Ambil item detail (0–n-1)
        $itemDetails = array_slice($data, 0, -1);

        // Ambil header data (elemen terakhir)
        $headerData = end($data);
        
        try {

            $purchaseOrder = self::create([
                'po_number' => $headerData['po_number'],
                'branch_id' => $headerData['branch_id'],
                'supplier_id' => $headerData['supplier_id'],
                'order_date' => $headerData['order_date'],
                'total' => $headerData['total'],
            ]);

            foreach ($itemDetails as $item) {
                PurchaseOrderDetail::create([
                    'po_number' => $headerData['po_number'],
                    'product_id' => $item['sku'],
                    'quantity' => $item['qty'],
                    'amount' => $item['amount'],
                ]);
            }

            DB::commit();
            return $purchaseOrder;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function getPOByNumberAndStatusFD($poNumber)
    {
        return self::where('po_number', $poNumber)
            ->where('status', POStatus::FD->value)
            ->first();
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

     //hitung jumlah order dari supplier tertentu untuk rentang waktu tertentu
    public static function countOrdersByDateSupplier(
        string $startDate,
        string $endDate,
        string $supplierID,
        ?POStatus $status = null
     ): int {
         $query = self::query()
            ->where('supplier_id', $supplierID)
            ->whereBetween('order_date', [$startDate, $endDate]);

        if (!is_null($status)) {
                $query->where('status', $status->value);
        }

         return $query->count();
    }

    public static function getReportBySupplierAndDate($supplierId, $startDate, $endDate)
    {
        return self::with(['supplier', 'details'])
            ->where('supplier_id', $supplierId)
            ->whereBetween('order_date', [$startDate, $endDate])
            ->orderBy('order_date', 'desc')
            ->get();
    }

    public static function countStatusPO()
    {
        return self::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');
    }

    public static function getPendingDeliveryQuantity($poNumber)
    {
        $poDetails = PurchaseOrderDetail::where('po_number', $poNumber)->get();
        
        $pendingDeliveries = [];
        
        if ($poDetails->isEmpty()) {
            return $pendingDeliveries; 
        }

        foreach ($poDetails as $detail) {
            $orderedQty = $detail->quantity;
            
            $receivedQty = GoodsReceiptNote::where('po_number', $poNumber)
                ->where('product_id', $detail->product_id)
                ->sum('delivered_quantity'); 
            
            $pendingQty = $orderedQty - $receivedQty;
            
            if ($pendingQty > 0) {
                $pendingDeliveries[] = [
                    'product_id' => $detail->product_id,
                    'ordered_qty' => $orderedQty,
                    'received_qty' => $receivedQty,
                    'pending_qty' => $pendingQty
                ];
            }
        }
        return $pendingDeliveries;
    }

    public static function getPurchaseOrderByStatus($status)
    {
        return self::where('status', $status)->get();
    }
}
