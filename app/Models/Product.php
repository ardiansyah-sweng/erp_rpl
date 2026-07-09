<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasDynamicColumns;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\Category; 

class Product extends Model
{
    use HasFactory, HasDynamicColumns;

    protected $table = 'products';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'product_id',
        'product_name',
        'product_type',
        'product_category',
        'product_description',
        'created_at',
        'updated_at',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('db_constants.table.products') ?? 'products';
        $configFillable = config('db_constants.column.products');
        if ($configFillable) {
            $this->fillable = array_values($configFillable);
        }
    }

    public static function updateProduct($id, array $data)
    {
        $product = self::find($id);

        if (!$product) {
            return null;
        }

        $product->update($data);

        return $product;
    }

    public function category() {
        return $this->belongsTo(Category::class, 'product_category', 'id');
    }

    public static function getAllProducts() {
        return self::withCount('items')->with('category')->selectRaw('(SELECT COUNT(*) FROM item WHERE item.sku LIKE CONCAT(products.product_id, "%")) AS items_count')->orderBy('created_at', 'desc')->paginate(10);
    }

    public function getSKURawMaterialItem() {
        return null;
    }

    public static function countProduct() {
        return self::count();
    }

    public static function addProduct($data) {
        return self::create($data);
    }

    public function getProductById($id) {
        return self::where('product_id', $id)->first();
    }    

    public static function countProductByProductType($shortType) {
        return 0;
    }

    public static function getProductByType($type) {
         return self::where('product_type', $type)->get();
    }

    public function items() {
        return $this->hasMany(Item::class, 'sku', 'product_id');
    }

    public static function deleteProductById($id) {
        $product = self::find($id);
        if ($product) { $product->delete(); return true; }
        return false;
    }

    public static function countProductByCategory() {
        return [];
    }
}