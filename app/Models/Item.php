<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Constants\ItemColumns;
use App\Constants\Messages;
use Exception;

class Item extends Model
{
    use HasFactory;

    protected $table;
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Use table name from config
        $this->table = config('db_tables.item', 'items');
        
        // Use fillable columns from ItemColumns constant
        $this->fillable = ItemColumns::getFillable();
    }

    // Relasi berdasarkan SKU ke PurchaseOrderDetail
    public function purchaseOrderDetails()
    {
        return $this->hasMany(PurchaseOrderDetail::class, ItemColumns::PROD_ID, ItemColumns::SKU);
    }

    // Relasi ke MeasurementUnit
    public function unit()
    {
        return $this->belongsTo(MeasurementUnit::class, ItemColumns::MEASUREMENT, ItemColumns::ID);
    }

    // Ambil semua item
    public function getItem()
    {
        return self::all();
    }

    // Ambil semua item dengan pencarian opsional
    public static function getAllItems($search = null)
    {
        $query = self::with('unit');

        if ($search) {
            if (is_numeric($search)) {
                $query->where(ItemColumns::ID, '=', $search);
            } else {
                $query->where(function ($q) use ($search) {
                    $q->where(ItemColumns::NAME, 'LIKE', "%{$search}%")
                      ->orWhere(ItemColumns::SKU, 'LIKE', "%{$search}%");
                });
            }
        }

        return $query->orderBy(ItemColumns::ID, 'asc')->paginate(10);
    }

    // Hapus item berdasarkan ID
    public static function deleteItemById($id)
    {
        $item = self::find($id);

        if (!$item) {
            return false;
        }

        // Cek relasi dengan purchase order
        if ($item->purchaseOrderDetails()->exists()) {
            throw new Exception(Messages::ITEM_IN_USE);
        }

        $item->delete();
        self::where(ItemColumns::ID, '>', $id)->decrement(ItemColumns::ID);

        return true;
    }

    // Hitung total item
    public static function countItem()
    {
        return self::count();
    }

    // Update data item
    public static function updateItem($id, $data)
    {
        $item = self::find($id);

        if (!$item) {
            return null;
        }

        $item->update($data);
        return $item;
    }

    // Tambah item baru
    public function addItem($data)
    {
        return self::create($data);
    }

    // Ambil item berdasarkan ID
    public static function getItemById($id)
    {
        return self::where(ItemColumns::ID, $id)->first();
    }

    // Hitung item berdasarkan tipe produk
    public static function countItemByProductType()
    {
        return self::count();
    }

    // Ambil item berdasarkan tipe produk
    public static function getItemByType($productType)
    {
        return self::join('products', 'items.' . ItemColumns::PROD_ID, '=', 'products.product_id')
            ->where('products.product_type', $productType)
            ->select('items.*', 'products.product_type', 'products.product_name')
            ->get();
    }

    // Cari item berdasarkan keyword
    public static function searchItem($keyword)
    {
        return self::where(ItemColumns::NAME, 'like', '%' . $keyword . '%')->paginate(10);
    }

    // Ambil item berdasarkan kategori produk
    public static function getItemByCategory($categoryId)
    {
        return self::join('products', 'items.' . ItemColumns::PROD_ID, '=', 'products.product_id')
            ->join('category', 'products.product_category', '=', 'category.id')
            ->where('category.id', $categoryId)
            ->select(
                'items.*',
                'products.product_name',
                'products.product_category',
                'category.category as category_name'
            )
            ->get();
    }

    // Hitung jumlah item dalam kategori tertentu
    public static function countItemByCategory($categoryId)
    {
        return self::join('products', 'items.' . ItemColumns::PROD_ID, '=', 'products.product_id')
            ->join('category', 'products.product_category', '=', 'category.id')
            ->where('category.id', $categoryId)
            ->count();
    }
}
