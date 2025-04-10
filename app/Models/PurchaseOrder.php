<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PurchaseOrder extends Model
{
    protected $table;
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Tetapkan nama tabel dan kolom dari konfigurasi
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
        return self::with('supplier')->orderBy('order_date', 'desc')->paginate(10);
    }

    public static function getPurchaseOrderByID($po_number)
    {
        return self::with('supplier', 'details')
                    ->where('po_number', $po_number)
                    ->orderBy('po_number')
                    ->paginate(10);
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
}
