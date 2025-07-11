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
        'product_description'
    ];

<<<<<<< HEAD
    // Maps the column names to their actual DB names
    protected $columnMap;
=======
    protected $casts = [
    'product_type' => \App\Enums\ProductType::class,
    ];
>>>>>>> origin/development

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('db_constants.table.products');
        
        $colProduct = config('db_constants.column.products');
        $this->columnMap = [
            'product_id' => $colProduct['id'],
            'product_name' => $colProduct['name'],
            'product_type' => $colProduct['type'],
            'product_category' => $colProduct['category'],
            'product_description' => $colProduct['desc'],
            'created_at' => $colProduct['created'],
            'updated_at' => $colProduct['updated']
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'product_category', 'id');
    }

    public static function getProduct()
    {
        $product = new self();
        return self::with(['category' => function($query) {
            $query->select('id', 'category');
        }])
        ->select(
            'id',
            $product->columnMap['product_id'] . ' as product_id',
            $product->columnMap['product_name'] . ' as product_name',
            $product->columnMap['product_type'] . ' as product_type',
            $product->columnMap['product_category'] . ' as product_category',
            $product->columnMap['product_description'] . ' as product_description',
            $product->columnMap['created_at'] . ' as created_at',
            $product->columnMap['updated_at'] . ' as updated_at'
        )
        ->orderBy($product->columnMap['created_at'], 'desc')
        ->paginate(10);
    }

    public static function getAllProducts()
    {
<<<<<<< HEAD
        return self::getProduct();
    }

    public static function getAllProductsForPDF()
    {
        $product = new self();
        return self::with(['category' => function($query) {
            $query->select('id', 'category');
        }])
        ->select(
            'id',
            $product->columnMap['product_id'] . ' as product_id',
            $product->columnMap['product_name'] . ' as product_name',
            $product->columnMap['product_type'] . ' as product_type',
            $product->columnMap['product_category'] . ' as product_category',
            $product->columnMap['product_description'] . ' as product_description',
            $product->columnMap['created_at'] . ' as created_at',
            $product->columnMap['updated_at'] . ' as updated_at'
        )
        ->orderBy($product->columnMap['created_at'], 'desc')
        ->get();
=======
        return self::withCount('items')->with('category')->selectRaw('(SELECT COUNT(*) FROM item WHERE item.sku LIKE CONCAT(products.product_id, "%")) AS items_count')->orderBy('created_at', 'desc')->paginate(10);
>>>>>>> origin/development
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
        $colProduct = config('db_constants.column.products');

        return self::where($colProduct['type'], $shortType)->count();
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
