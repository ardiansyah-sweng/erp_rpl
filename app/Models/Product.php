<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasDynamicColumns;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
        return $this->belongsTo(Category::class, ProductColumns::CATEGORY, 'id');
    }

    public function categoryRelation()
    {
        return $this->belongsTo(Category::class, ProductColumns::CATEGORY, 'id');
    }

    public static function getAllProducts(?string $keywords = null, bool $shouldPaginate = true, int $perPage = 10)
    {
        $productTable = (new self())->getTable();
        $itemTable = config('db_tables.item', config('db_constants.table.item', 'items'));

        if (!Schema::hasTable($itemTable) && Schema::hasTable('item')) {
            $itemTable = 'item';
        }

        $query = self::with('categoryRelation')
            ->select("{$productTable}.*")
            ->selectRaw("(SELECT COUNT(*) FROM {$itemTable} WHERE {$itemTable}.sku LIKE CONCAT({$productTable}.product_id, '%')) AS items_count");

        if ($keywords !== null && $keywords !== '') {
            $query->where(function ($productQuery) use ($keywords) {
                $productQuery->where(ProductColumns::PRODUCT_ID, 'LIKE', "%{$keywords}%")
                    ->orWhere(ProductColumns::NAME, 'LIKE', "%{$keywords}%")
                    ->orWhere(ProductColumns::TYPE, 'LIKE', "%{$keywords}%")
                    ->orWhere(ProductColumns::CATEGORY, 'LIKE', "%{$keywords}%")
                    ->orWhere(ProductColumns::DESC, 'LIKE', "%{$keywords}%")
                    ->orWhereHas('categoryRelation', function ($categoryQuery) use ($keywords) {
                        $categoryQuery->where('category', 'LIKE', "%{$keywords}%");
                    });
            });
        }

        $query->orderBy('created_at', 'desc');

        return $shouldPaginate ? $query->paginate($perPage) : $query->get();
    }

    public function getSKURawMaterialItem()
    {
        $tableItem = config('db_constants.table.item');
        $colItem = config('db_constants.column.item');
        $colProduct = config('db_constants.column.products');

        return Item::query()
                        ->from($tableItem . ' as items')
                        ->join($this->table, $this->table.'.'.$colProduct['id'], '=', 'items.'.$colItem['prod_id'])
                        ->distinct()
                        ->where($this->table.'.'.$colProduct['type'], 'RM')
                        ->select('items.'.$colItem['sku']);
    }

    public static function addProduct($data)
    {
        return self::create($data);
    }

    public function getProductById($id) {
        return self::with('categoryRelation')->where('product_id', $id)->first();
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
        return $this->hasMany(Item::class, 'product_id', 'product_id');
    }

    public static function getProductByCategory($productCategory)
    {
        return self::with('category')
            ->where(ProductColumns::CATEGORY, $productCategory)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
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

    public static function getProductByKeyword($keywords = null)
    {
        return self::getAllProducts($keywords);
    }
}
