<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Constants\ItemColumns;
use App\Constants\Messages;
use Exception;

class Item extends Model
{
    use HasFactory;
    
    protected $table;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Use table name from config
        $this->table = config('db_tables.item', 'items');
        
        // Use fillable columns from ItemColumns constant
        $this->fillable = ItemColumns::getFillable();
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
            throw new Exception(Messages::ITEM_IN_USE);
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

    public static function countItemByProductType(){
        return self::count(); 
    }

    
    public static function getItemByType($productType)
    {
        return self::join('products', 'item.product_id', '=', 'products.product_id')
            ->where('products.product_type', $productType)
            ->select('item.*', 'products.product_type', 'products.product_name')
            ->get();
    }

    public static function searchItem($keyword)
    {
        return self::where('item_name', 'like', '%' . $keyword . '%')->paginate(10);
    }
    
    public static function getItemByCategory($categoryId)
    {
        return self::join('products', 'item.product_id', '=', 'products.product_id')
            ->join('category', 'products.product_category', '=', 'category.id')
            ->where('category.id', $categoryId)
            ->select(
                'item.*',
                'products.product_name',
                'products.product_category',
                'category.category as category_name'
            )
            ->get();
    }

    public static function countItemByCategory($categoryId)
    {
        return self::join('products', 'item.product_id', '=', 'products.product_id')
            ->join('category', 'products.product_category', '=', 'category.id')
            ->where('category.id', $categoryId)
            ->count();
    }
}
