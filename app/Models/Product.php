<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasDynamicColumns;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\Category; 
use App\Enums\ProductType;
<<<<<<< HEAD
=======
use App\Constants\ProductColumns;
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d

class Product extends Model
{
    use HasFactory, HasDynamicColumns;

    protected $table = 'products';
    protected $fillable = [
<<<<<<< HEAD
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
=======
        ProductColumns::PRODUCT_ID,
        ProductColumns::NAME,
        ProductColumns::TYPE,
        ProductColumns::CATEGORY,
        ProductColumns::DESC,
    ];

    protected $casts = [
        ProductColumns::TYPE => ProductType::class,
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
<<<<<<< HEAD

        $this->table = config('db_constants.table.products');
        $this->fillable = array_values(config('db_constants.column.products') ?? []);
=======
        // Comment out config override for now to use the correct column names
        // $this->table = config('db_constants.table.products');
        // $this->fillable = array_values(config('db_constants.column.products') ?? []);
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    }

    public function category()
    {
<<<<<<< HEAD
        return $this->belongsTo(Category::class, 'product_category', 'id');
=======
        return $this->belongsTo(Category::class, 'category', 'id'); // ubah dari product_category ke category
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    }

    public static function getAllProducts()
    {
<<<<<<< HEAD
        return self::withCount('items')->with('category')->selectRaw('(SELECT COUNT(*) FROM item WHERE item.sku LIKE CONCAT(products.product_id, "%")) AS items_count')->orderBy('created_at', 'desc')->paginate(10);
=======
        $tableItem = config('db_constants.table.item');
        return self::withCount('items')->with('category')->selectRaw("(SELECT COUNT(*) FROM {$tableItem} WHERE {$tableItem}.sku LIKE CONCAT(products.product_id, \"%\")) AS items_count")->orderBy('created_at', 'desc')->paginate(10);
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
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
<<<<<<< HEAD
        $colProduct = config('db_constants.column.products');

        return self::where($colProduct['type'], $shortType)->count();
=======
        // Use canonical column constant to avoid relying on test env config
        return self::where(ProductColumns::TYPE, $shortType)->count();
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    }

    public static function getProductByType($type)
    {
<<<<<<< HEAD
         return self::where('product_type', $type)->get();
=======
         return self::where('type', $type)->get();
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
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
<<<<<<< HEAD
            ->select('product_category', DB::raw('COUNT(*) as total'))
            ->groupBy('product_category')
            ->get();
    }
=======
            ->select('category as product_category', DB::raw('COUNT(*) as total'))
            ->groupBy('category')
            ->get();
    }

    public static function getProductByKeyword($keywords = null)
    {
        $query = self::query();

        if ($keywords) {
            $query->where(ProductColumns::PRODUCT_ID, 'LIKE', "%{$keywords}%")
                  ->orWhere(ProductColumns::NAME, 'LIKE', "%{$keywords}%")
                  ->orWhere(ProductColumns::TYPE, 'LIKE', "%{$keywords}%")
                  ->orWhere(ProductColumns::CATEGORY, 'LIKE', "%{$keywords}%")
                  ->orWhere(ProductColumns::DESC, 'LIKE', "%{$keywords}%");
        }

        return $query->orderBy('created_at', 'asc')->paginate(10);
    }
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
}
