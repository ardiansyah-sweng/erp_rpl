<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicColumns;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\Category; 
use App\Enums\ProductType;

class Product extends Model
{
    use HasDynamicColumns;

    protected $table = 'products';
    protected $fillable = [
        'product_id',
        'product_name',
        'product_type',
        'product_category',
        'product_description'
    ];

    // Maps the column names to their actual DB names
    protected $columnMap;

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
        return self::where('id', $id)->first();
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

}
