<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Category extends Model
{
    use HasFactory;

    protected $table;
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('db_constants.table.category', 'categories'); // Default ke 'categories' jika tidak ditemukan di config
        $this->fillable = array_values(config('db_constants.column.category', ['category', 'parent_id', 'active', 'created_at', 'updated_at']));
    }


    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    // Relasi ke kategori induk
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public static function addCategory(array $data)
    {
        return self::create($data);
    }

    public static function countCategory()
    {
        return self::count();
    }
    // mengambil semua kategori beserta data induknya
    public static function getCategory()
    {
        return self::with('parent')->get();
    }

    public static function getCategoryById($id)
    {
        $category = self::with('parent:id,category')->find($id);

        if (!$category) {
            return null;
        }

        $category->parent_id = optional($category->parent)->category ?? 'Tanpa Induk';

        unset($category->parent);
        unset($category->parent_name);

        return $category;
    }
    public static function countByParent()
    {
        $instance = new static;
        $table = $instance->getTable();

        return self::join($table . ' as parent', $table . '.parent_id', '=', 'parent.id')
            ->selectRaw('parent.id as parent_id, parent.category as category_name, COUNT(*) as total')
            ->groupBy('parent.id', 'parent.category')
            ->get()
            ->map(function ($item) {
                return [
                    'parent_id' => $item->parent_id,
                    'category' => $item->category_name,
                    'total' => $item->total,
                ];
            });
    }

    public static function updateCategory($category_id, array $data)
    {
        $category = self::find($category_id);
        if (!$category) {
            return null;
        }

        $fillable = (new self)->getFillable();
        $filteredData = array_intersect_key($data, array_flip($fillable));
        $category->update($filteredData);

        return $category;
    }
     // delete category
    public static function deleteCategoryById($id)
    {
        // Cek apakah kategori digunakan di tabel produk
        $isUsed = Product::where('product_category', $id)->exists();

        if ($isUsed) {
            return false;
        }

        $category = self::find($id);

        if ($category) {
            return $category->delete();
        }

        return false;
    }

    ////dapatkan produk dari parent
    public static function getCategoryByParent($id)
    {
         return self::where('parent_id', $id)->get();
    }


    //search
    public static function searchCategory($keyword)
    {
        return self::where('category', 'LIKE', '%' . $keyword . '%')
                    ->with('parent')
                    ->get();
    }   

}
