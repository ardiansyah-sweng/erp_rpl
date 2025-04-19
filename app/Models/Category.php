<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;


    protected $table = 'category';

    protected $fillable = ['category', 'parent_id', 'active', 'created_at', 'updated_at'];

    // Relasi ke produk
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    // Relasi ke kategori induk
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Relasi ke sub-kategori
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Menghitung total semua kategori
    public static function countCategory()
    {
        return self::count();
    }

    // Menghitung jumlah kategori per parent (dalam format array seperti yang dosen minta)
    public static function countByParent()
    {
        return self::select('parent.category as name')
            ->join('category as parent', 'category.parent_id', '=', 'parent.id')
            ->selectRaw('COUNT(category.id) as total')
            ->groupBy('category.parent_id', 'parent.category')
            ->get()
            ->map(function ($item) {
                return [$item->name, $item->total];
            })
            ->toArray();
    }
}
