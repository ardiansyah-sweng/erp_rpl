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

    protected $table;
    protected $fillable = [];

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

    public function items()
    {
        $tableItem = config('db_constants.table.item');
        $colItem = config('db_constants.column.item');
        $colProduct = config('db_constants.column.products');

        return $this->hasMany(Item::class, 'sku', 'product_id');
    }

    public static function getAllProducts()
    {
        return self::withCount('items') 
                    ->with('category')  
                    ->selectRaw('(SELECT COUNT(*) FROM item WHERE item.sku LIKE CONCAT(products.product_id, "%")) AS items_count')
                    ->orderBy('created_at', 'desc')  
                    ->paginate(10);  
                    
    }

    public function getSKURawMaterialItem()
    {
        $tableItem = config('db_constants.table.item');
        $colItem = config('db_constants.column.item');
        $colProduct = config('db_constants.column.products');

        return Item::join($this->table, DB::raw('SUBSTRING(' . $tableItem . '.' . $colItem['sku'] . ', 1, 4)'), '=', $this->table . '.' . $colProduct['id'])
            ->where($this->table . '.product_type', ProductType::RM)
            ->select($tableItem . '.' . $colItem['sku'])
            ->pluck($colItem['sku']);
    }

    public function getProductTypeAttribute($value)
    {
        return match ($value) {
            'RM' => 'Raw Material',
            'FG' => 'Finished Good',
            'HFG' => 'Half Finish Good',
            default => $value,
        };
    }


}
