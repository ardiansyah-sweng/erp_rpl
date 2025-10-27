<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * Menggunakan 'items' (plural) sesuai konvensi Laravel.
     * @var string
     */
    protected $table;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // FIX: Menggunakan konstanta untuk nama tabel agar konsisten.
        $this->table = config('db_constants.table.item');
    }

    /**
     * The attributes that are mass assignable.
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'sku',
        'item_name',
        'avg_base_price',
        'selling_price',
        'measurement_unit_id',
        'purchase_unit_id',
        'sell_unit_id',
        'stock_unit_id'
    ];

    /**
     * Mendapatkan produk yang memiliki item ini.
     */
    public function product(): BelongsTo
    {
        // FIX: Menghubungkan 'product_id' (foreign key) di tabel 'items'
        // dengan 'id' (primary key) di tabel 'products'.
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * Mendapatkan satuan ukuran untuk item ini.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(MeasurementUnit::class, 'measurement_unit_id', 'id');
    }

    /**
     * Relasi ke PurchaseOrderDetail berdasarkan SKU.
     */
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
                $query->where(function ($q) use ($search) {
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

        if ($item->purchaseOrderDetails()->exists()) {
            throw new Exception(Messages::ITEM_IN_USE);
        }

        $item->delete();
        // Peringatan: Logika decrement id mungkin berbahaya untuk konsistensi data
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

    public static function getItembyId($id)
    {
        return self::where('id', $id)->first();
    }

    public static function countItemByProductType()
    {
        return self::count();
    }

    /**
     * Mendapatkan item berdasarkan tipe produk menggunakan relasi Eloquent.
     */
    public static function getItemByType($productType)
    {
        return self::whereHas('product', function ($query) use ($productType) {
            $query->where('product_type', $productType);
        })->with('product')->get();
    }

    public static function searchItem($keyword)
    {
        return self::where('item_name', 'like', '%' . $keyword . '%')->paginate(10);
    }

    /**
     * Mendapatkan item berdasarkan kategori produk menggunakan relasi Eloquent.
     */
    public static function getItemByCategory($categoryId)
    {
        return self::whereHas('product', function ($query) use ($categoryId) {
            $query->where('product_category', $categoryId);
        })->with('product.category')->get();
    }

    /**
     * Menghitung item berdasarkan kategori produk menggunakan relasi Eloquent.
     */
    public static function countItemByCategory($categoryId)
    {
        return self::whereHas('product', function ($query) use ($categoryId) {
            $query->where('product_category', $categoryId);
        })->count();
    }
}
