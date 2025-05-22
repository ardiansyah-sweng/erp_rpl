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
        'product_description',
        'created_at',
        'updated_at',
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
        return self::with('category')->orderBy('created_at', 'desc')->paginate(10);
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
    
    public static function getUpdateProduct($id, array $data)
    {
    $product = self::find($id);
    if (!$product) {
        return null;
    }

    $fillable = (new self)->getFillable();
    $filteredData = array_intersect_key($data, array_flip($fillable));
    $filteredData['updated_at'] = now(); // Untuk pastikan updated_at

    $product->update($filteredData);

    return $product;
    }

    
}
