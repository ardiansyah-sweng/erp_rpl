<?php

namespace Tests\Unit;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase; 
use Tests\TestCase;

class CategoryTest extends TestCase
{
      
    public function test_it_can_delete_an_unused_category_successfully()
    {
        // Ambil semua ID kategori yang sedang digunakan oleh produk
        $usedCategoryIds = DB::table('products')->pluck('product_category');
        
         // Ambil satu kategori yang tidak digunakan
        $category = Category::whereNotIn('id', $usedCategoryIds)->first();

        $this->assertNotNull($category, 'Tidak ditemukan kategori yang tidak digunakan.');

        // Coba hapus kategori
        $result = Category::deleteCategoryById($category->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('category', ['id' => $category->id]);
    }

    public function test_it_fails_to_delete_a_category_that_is_in_use_by_a_product()
    {
        // Ambil satu ID kategori yang digunakan oleh produk
        $categoryIdUsed = DB::table('products')
            ->select('product_category')
            ->whereNotNull('product_category')
            ->limit(1)
            ->pluck('product_category')
            ->first();
        
        $category = Category::find($categoryIdUsed);

        $this->assertNotNull($category, 'Tidak ditemukan kategori yang sedang digunakan.');

        // Coba hapus kategori yang sedang digunakan
        $result = Category::deleteCategoryById($category->id);

        // Assert
        $this->assertFalse($result);
        $this->assertDatabaseHas('category', ['id' => $category->id]);
    }

    public function test_it_returns_false_when_trying_to_delete_a_non_existent_category()
    {
        // Ambil ID kategori tertinggi dan tambahkan 1 supaya tidak ada di database
        $nonExistentId = Category::max('id') + 1;

        // Coba hapus kategori yang tidak ada
        $result = Category::deleteCategoryById($nonExistentId);

        // Assert
        $this->assertFalse($result);
    }
}
