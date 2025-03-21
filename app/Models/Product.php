<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicColumns;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Enums\ProductType;

class Product extends Model
{
    use HasDynamicColumns;

    protected $tableProduct;
    protected $fillableProduct = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Tetapkan nama tabel dan kolom
        $this->tableProduct = config('db_constants.table.products');
        $this->fillableProduct = array_values(config('db_constants.column.products') ?? []);
    }


    public static function getAllProducts()
    {
        return DB::table(config('db_constants.table.products'))->get();
    }


    public function getSKURawMaterialItem()
    {
        $tableItem = config('db_constants.table.item');
        $colItem = config('db_constants.column.item');
        $colProduct = config('db_constants.column.products');

        return Item::join($this->tableProduct, DB::raw('SUBSTRING(' . $tableItem . '.' . $colItem['sku'] . ', 1, 4)'), '=', $this->tableProduct . '.' . $colProduct['id'])
                        ->where($this->tableProduct . '.product_type', ProductType::RM)
                        ->select($tableItem . '.' . $colItem['sku'])
                        ->pluck($colItem['sku']);
    }
}
