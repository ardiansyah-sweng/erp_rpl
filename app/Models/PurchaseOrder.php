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
        //DB::beginTransaction();

        $allData = $data->all();
        // $headerData = end($allData);

        $purchaseOrder = self::create([
            'po_number' => $allData['po_number'],
            'branch_id' => $allData['branch_id'],
            'supplier_id' => $allData['supplier_id'],
            'order_date' => $allData['order_date'],
        ]);

        // try {

        //     $purchaseOrder = self::create([
        //         'po_number' => $headerData['po_number'],
        //         'branch_id' => $headerData['branch_id'],
        //         'supplier_id' => $headerData['supplier_id'],
        //         'order_date' => $headerData['order_date'],
        //     ]);

        //     $subtotal = 0;

        //     foreach ($data['items'] as $item) {
        //         $amount = $item['qty'] * $item['unit_price'];
        //         $subtotal += $amount;

        //         PurchaseOrderDetail::create([
        //             'po_number' => $poNumber,
        //             'sku' => $item['sku'],
        //             'item_name' => $item['item_name'],
        //             'qty' => $item['qty'],
        //             'amount' => $amount,
        //         ]);
        //     }

        //     $tax = $subtotal * 0.1;
        //     $purchaseOrder->update([
        //         'subtotal' => $subtotal,
        //         'tax' => $tax,
        //         'total' => $subtotal + $tax,
        //     ]);

        //     DB::commit();
        //     return $purchaseOrder;

        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     throw $e;
        // }
    }
}
