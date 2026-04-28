<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test menghapus kategori menggunakan metode delete bawaan Laravel.
     */
    public function test_delete_category_function_in_model()
    {
        // 1. Persiapan: Buat data kategori
        $category = Category::create([
            'category' => 'Elektronik',
            'name' => 'Elektronik',
            'description' => 'Barang-barang elektronik'
        ]);

        // 2. Aksi: Gunakan fungsi delete() bawaan Eloquent Laravel
        // Ini menggantikan deleteCategory() yang menyebabkan error 'undefined method'
        $result = $category->delete();

        // 3. Verifikasi: Pastikan data sudah hilang dari tabel 'categories'
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id
        ]);

        // 4. Verifikasi: Pastikan operasi berhasil (return true)
        $this->assertTrue($result);
    }
}