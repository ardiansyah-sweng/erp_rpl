<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicColumns;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\Category; // Tambahkan Model Category
use App\Enums\ProductType;

class Product extends Model
{
    use HasDynamicColumns;

    protected $table;
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Tetapkan nama tabel dan kolom dari konfigurasi
        $this->table = config('db_constants.table.products');
        $this->fillable = array_values(config('db_constants.column.products') ?? []);
    }

    /**
     * Relasi ke kategori
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'product_category', 'id');
        // 'product_category' = foreign key di tabel products
        // 'id' = primary key di tabel categories
    }

    /**
     * Ambil semua produk
     */
    public static function getAllProducts()
    {
        return DB::table(config('db_constants.table.products'))->get();
    }

    /**
     * Ambil SKU dari item bahan mentah
     */
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
}
