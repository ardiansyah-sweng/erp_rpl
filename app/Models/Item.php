<?php

namespace App\Models;

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Model;
=======
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Constants\ItemColumns;
use App\Constants\Messages;
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
use Exception;

class Item extends Model
{
<<<<<<< HEAD
    protected $table = 'item';
    protected $fillable = [
        'product_id', 'sku', 'item_name', 'measurement_unit',
        'avg_base_price', 'selling_price', 'purchase_unit',
        'sell_unit', 'stock_unit'
    ];
=======
    use HasFactory;

    protected $table;
    protected $fillable = [];
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

<<<<<<< HEAD
        $this->table = config('db_constants.table.item');
        $this->fillable = array_values(config('db_constants.column.item') ?? []);
    }

    // Relasi berdasarkan sku
    public function purchaseOrderDetails()
    {
        return $this->hasMany(PurchaseOrderDetail::class, 'product_id', 'sku');
    }

=======
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
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    public function getItem()
    {
        return self::all();
    }

<<<<<<< HEAD
=======
    // Ambil semua item dengan pencarian opsional
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    public static function getAllItems($search = null)
    {
        $query = self::with('unit');

        if ($search) {
            if (is_numeric($search)) {
<<<<<<< HEAD
                $query->where('id', '=', $search);
            } else {
                $query->where(function($q) use ($search) {
                    $q->where('item_name', 'LIKE', "%{$search}%")
                      ->orWhere('sku', 'LIKE', "%{$search}%");
=======
                $query->where(ItemColumns::ID, '=', $search);
            } else {
                $query->where(function ($q) use ($search) {
                    $q->where(ItemColumns::NAME, 'LIKE', "%{$search}%")
                      ->orWhere(ItemColumns::SKU, 'LIKE', "%{$search}%");
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
                });
            }
        }

<<<<<<< HEAD
        return $query->orderBy('id', 'asc')->paginate(10);
    }

=======
        return $query->orderBy(ItemColumns::ID, 'asc')->paginate(10);
    }

    // Hapus item berdasarkan ID
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    public static function deleteItemById($id)
    {
        $item = self::find($id);

        if (!$item) {
            return false;
        }

<<<<<<< HEAD
        // Cek relasi berdasarkan SKU
        if ($item->purchaseOrderDetails()->exists()) {
            throw new Exception("Item tidak bisa dihapus karena sudah digunakan di purchase order.");
        }

        $item->delete();
        self::where('id', '>', $id)->decrement('id');
=======
        // Cek relasi dengan purchase order
        if ($item->purchaseOrderDetails()->exists()) {
            throw new Exception(Messages::ITEM_IN_USE);
        }

        $item->delete();
        self::where(ItemColumns::ID, '>', $id)->decrement(ItemColumns::ID);
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d

        return true;
    }

<<<<<<< HEAD
    public static function countItem() 
=======
    // Hitung total item
    public static function countItem()
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    {
        return self::count();
    }

<<<<<<< HEAD
    public static function updateItem($id, $data)
    {
        $item = self::find($id);
    
        if (!$item) {
            return null;
        }
    
        $item->update($data);
    
        return $item;
       }


=======
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
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    public function addItem($data)
    {
        return self::create($data);
    }

<<<<<<< HEAD
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
=======
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
            ->where('products.type', $productType)
            ->select('items.*', 'products.type as product_type', 'products.name as product_name')
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
        return self::query()
        ->join('products', 'items.product_id', '=', 'products.product_id')
        ->join('categories', 'products.category', '=', 'categories.id')
        ->where('categories.id', $categoryId)
        ->select(
            'items.*',
            'products.name as product_name',
            'products.category',
            'categories.category as category_name' 
        )
        ->get();

    }

    // Hitung jumlah item dalam kategori tertentu
    public static function countItemByCategory($categoryId)
    {
        return self::join('products', 'items.' . ItemColumns::PROD_ID, '=', 'products.product_id')
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
            ->join('category', 'products.product_category', '=', 'category.id')
            ->where('category.id', $categoryId)
            ->count();
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
