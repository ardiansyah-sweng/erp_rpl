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
}
