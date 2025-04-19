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
    
    public static function updateProduct($id, array $input)
    {
        $product = self::find($id);
        
        if (!$product) {
            return [
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ];
        }
        
        DB::beginTransaction();
        
        try {
            $productColumns = config('db_constants.column.products', []);
            
            foreach ($productColumns as $key => $column) {
                if ($column != 'id' && isset($input[$column])) {
                    $product->$column = $input[$column];
                }
            }
            
            $now = now();
            $product->updated_at = $now;
            
            $product->save();
            
            DB::commit();
            
            return [
                'success' => true,
                'message' => 'Produk berhasil diperbarui',
                'data' => $product
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Gagal memperbarui produk', [
                'product_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ];
        }
    }
  
}
