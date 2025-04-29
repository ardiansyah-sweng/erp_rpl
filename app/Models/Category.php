<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table;
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);


        $this->table = config('db_constants.table.category', 'categories'); // Default ke 'categories' jika tidak ditemukan
        $this->fillable = array_values(config('db_constants.column.category', ['category', 'parent_id', 'active', 'created_at', 'updated_at']));
    }


    // Relasi: Kategori memiliki banyak produk.

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }



    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }


    //  Relasi: Kategori memiliki banyak anak (sub kategori).

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Menghitung total semua kategori.
     *
     * @return int
     */
    public static function countCategory()
    {
        return self::count();
    }

    /**
     * Menghitung jumlah kategori berdasarkan parent category.
     *
     * @return array
     */
    public static function countByParent()
    {
        $instance = new static;
        $table = $instance->getTable();

        return self::join($table . ' as parent', $table . '.parent_id', '=', 'parent.id')
            ->selectRaw('parent.category as name, COUNT(' . $table . '.id) as total')
            ->groupBy($table . '.parent_id', 'parent.category')
            ->get()
            ->map(function ($item) {
                return [
                    $item->name,
                    $item->total,
                ];
            })
            ->toArray();
    }
}
