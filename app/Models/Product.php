<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasDynamicColumns;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\Category; 
use App\Enums\ProductType;
use App\Constants\ProductColumns;

class Product extends Model
{
    use HasFactory, HasDynamicColumns;

    protected $table = 'products';
    protected $fillable = [
        ProductColumns::PRODUCT_ID,
        ProductColumns::NAME,
        ProductColumns::TYPE,
        ProductColumns::CATEGORY,
        ProductColumns::DESC,
    ];

    protected $casts = [
        ProductColumns::TYPE => ProductType::class,
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // Comment out config override for now to use the correct column names
        // $this->table = config('db_constants.table.products');
        // $this->fillable = array_values(config('db_constants.column.products') ?? []);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category', 'id'); // ubah dari product_category ke category
    }

    public static function getAllProducts()
    {
        $tableItem = config('db_constants.table.item');
        return self::withCount('items')->with('category')->selectRaw("(SELECT COUNT(*) FROM {$tableItem} WHERE {$tableItem}.sku LIKE CONCAT(products.product_id, \"%\")) AS items_count")->orderBy('created_at', 'desc')->paginate(10);
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

    public static function addProduct($data)
    {
        return self::create($data);
    }

    public function getProductById($id) {
        return self::where('product_id', $id)->first();
    }    

    public static function countProductByProductType($shortType)
    {
        // Use canonical column constant to avoid relying on test env config
        return self::where(ProductColumns::TYPE, $shortType)->count();
    }

    public static function getProductByType($type)
    {
         return self::where('type', $type)->get();
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

    public static function deleteProductById($id)
    {
        $product = self::find($id);
        if (!$product) {
            return false;
        }

        $used = Item::where('product_id', $product->product_id)->exists();
        if ($used) {
            return false;
        }

        $product->delete();
        return true;
    }

    public static function countProductByCategory()
    {
        return DB::table('products')
            ->select('category as product_category', DB::raw('COUNT(*) as total'))
            ->groupBy('category')
            ->get();
    }
}
