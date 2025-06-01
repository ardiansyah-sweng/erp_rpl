<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;

class Item extends Model
{
    protected $table;
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('db_constants.table.item');
        $this->fillable = array_values(config('db_constants.column.item') ?? []);
    }

    // Relasi berdasarkan sku
    public function purchaseOrderDetails()
    {
        return $this->hasMany(PurchaseOrderDetail::class, 'product_id', 'sku');
    }

    public function getItem()
    {
        return self::all();
    }

    public static function getAllItems($search = null)
    {
        $query = self::with('unit');

        if ($search) {
            if (is_numeric($search)) {
                $query->where('id', '=', $search);
            } else {
                $query->where(function($q) use ($search) {
                    $q->where('item_name', 'LIKE', "%{$search}%")
                      ->orWhere('sku', 'LIKE', "%{$search}%");
                });
            }
        }

        return $query->orderBy('id', 'asc')->paginate(10);
    }

    public static function deleteItemById($id)
    {
        $item = self::find($id);

        if (!$item) {
            return false;
        }

        // Cek relasi berdasarkan SKU
        if ($item->purchaseOrderDetails()->exists()) {
            throw new Exception("Item tidak bisa dihapus karena sudah digunakan di purchase order.");
        }

        $item->delete();
        self::where('id', '>', $id)->decrement('id');

        return true;
    }

    public static function countItem() 
    {
        return self::count();
    }

    public static function updateItem($id, $data)
    {
        $item = self::find($id);
    
        if (!$item) {
            return null;
        }
    
        $item->update($data);
    
        return $item;
    }

    public function addItem($data)
    {
        return self::create($data);
    }

    public function unit()
    {
        return $this->belongsTo(MeasurementUnit::class, 'measurement_unit', 'id');
    }


    public static function getItembyId($id){
        return self::where('id', $id)->first();
    }
  
    public static function deleteItem($id)
    {
        $item = self::find($id);

        if (!$item) {
            return ['status' => false, 'message' => 'Item tidak ditemukan.'];
        }

        $used = \DB::table('purchase_order_detail')
            ->where('product_id', $id)
            ->exists();

        if ($used) {
            return ['status' => false, 'message' => 'Item tidak bisa dihapus karena sudah digunakan di purchase order.'];
        }

        $item->delete();

        return ['status' => true, 'message' => 'Item berhasil dihapus.'];
    }


}
