<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasDynamicColumns;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\Category; 
use App\Enums\ProductType;

class Product extends Model
{
    use HasFactory, HasDynamicColumns;

    protected $table = 'products';
    protected $fillable = [
        'product_id',
        'product_name',
        'product_type',
        'product_category',
        'product_description',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
    'product_type' => \App\Enums\ProductType::class,
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('db_constants.table.products');
        $this->fillable = array_values(config('db_constants.column.products') ?? []);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'product_category', 'id');
    }

    public static function getAllProducts()
    {
        return self::withCount('items')->with('category')->selectRaw('(SELECT COUNT(*) FROM item WHERE item.sku LIKE CONCAT(products.product_id, "%")) AS items_count')->orderBy('created_at', 'desc')->paginate(10);
    }

    public function getSKURawMaterialItem()
    {
        $tableItem = config('db_constants.table.item');
        $colItem = config('db_constants.column.item');
        $colProduct = config('db_constants.column.products');

        return Item::join($this->table, $this->table.'.'.$colProduct['id'], '=', $tableItem.'.'.$colItem['prod_id'])
                        ->distinct()
                        ->where($this->table.'.'.$colProduct['type'], 'RM')
                        ->select($tableItem.'.'.$colItem['sku']);
    }

    public static function countProduct() {
        return self::count();
    }

    public static function deleteProductById($id)
    {
        $product = self::find($id);

        if (!$product) {
            return false;
        }

        $itemCount = Item::where('product_id', $id)->count();
        
        if ($itemCount > 0) {
            return false;
        }
        
        $product->delete();
        
        return true;
    }

    public static function addProduct($data)
    {
        return self::create($data);
    }

    public function getProductById($id) {
        return self::where('product_id', $id)->first();
    }    

    public static function getProductByType($type)
    {
         return self::where('product_type', $type)->get();
    }

    public static function updateProduct($id, array $data)//Sudah sesuai pada ERP RPL
    {
        $product = self::find($id);
        if (!$product) {
            return null;
        }
        $product->update($data);

        return $product;
    }

    public function items()
    {
        $tableItem = config('db_constants.table.item');
        $colItem = config('db_constants.column.item');
        $colProduct = config('db_constants.column.products');

        return $this->hasMany(Item::class, 'sku', 'product_id');
    }

}
