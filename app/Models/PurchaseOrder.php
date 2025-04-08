<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function details(){
        return $this->hasMany(PurchaseOrderDetail::class, 'po_number', 'po_number');
    }

    public static function getAllPurchaseOrders()
    {
        // Mengurutkan supplier berdasarkan tanggal pesanan(order_date) secara Descending
        return self::with('supplier')->orderBy('order_date', 'desc')->paginate(10);
    }
    public static function getPurchaseOrderByKeywords($keywords = null)
    {
        $query = self::query();

        if ($keywords) {
            $query->where('po_number', 'LIKE', "%{$keywords}%")
                  ->orWhere('supplier_id', 'LIKE', "%{$keywords}%")
                  ->orWhere('status', 'LIKE', "%{$keywords}%");
        }

        return $query->orderBy('created_at', 'asc')->paginate(10);
    }


    public static function getPurchaseOrderByID($po_number)
    {
        return self::with('supplier', 'details')->orderBy('po_number')->where('po_number', $po_number)->paginate(10);
    }

}