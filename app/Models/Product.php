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
        $this->tableProduct = config('db_constants.table.product');
        $this->fillableProduct = array_values(config('db_constants.column.product') ?? []);

        $this->tableItem = config('db_constants.table.item');
        $this->colItem = config('db_constants.column.item');
        $this->colProduct = config('db_constants.column.product');
    }

    public function getSKURawMaterialItem()
    {
        #mendapatkan seluruh item random dari product bertipe RM (raw material)
        return Item::join($this->tableProduct, DB::raw('SUBSTRING(' . $this->tableItem . '.' .    $this->colItem['sku'] . ', 1, 4)'), '=', $this->tableProduct . '.' . $this->colProduct['id'])
                        ->where($this->tableProduct . '.product_type', ProductType::RM)
                        ->select($this->tableItem . '.' . $this->colItem['sku'])
                        ->pluck($this->colItem['sku']);
    }
}
