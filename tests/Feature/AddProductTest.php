<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Category;

class AddProductTest extends TestCase
{
    // Menggunakan DatabaseTransactions agar data test otomatis dihapus (rollback) setelah selesai
    // Ini sangat penting agar database utamamu tidak kotor oleh data-data hasil testing
    use DatabaseTransactions;

    /**
     * Test apakah halaman form Tambah Produk bisa dibuka dengan normal (Status 200 OK)
     */
    public function test_add_product_page_can_be_rendered()
    {
        $response = $this->get('/product/add');
        
        $response->assertStatus(200);
        $response->assertSee('ID Produk');
        $response->assertSee('Nama Produk');
    }

    /**
     * Test alur utama: Mengisi form, submit, dan pastikan data benar-benar tersimpan ke Database
     */
    public function test_can_add_product_successfully_to_database()
    {
        // 1. Persiapan: Buat kategori tiruan untuk kebutuhan test
        $category = Category::create([
            'category' => 'Kategori Automasi Test',
            'parent_id' => 0,
            'active' => 1
        ]);

        // 2. Aksi: Kirim request POST ke route dengan data form (Mirip seperti submit dari browser)
        $response = $this->post('/product/add', [
            'product_id' => 'T001',
            'product_name' => 'Produk Hasil Automasi',
            'product_type' => 'FG', // Harus sesuai Enum (FG, RM, HFG)
            'product_category' => $category->id, // Menggunakan ID kategori
            'product_description' => 'Deskripsi untuk testing',
        ]);

        // 3. Pengecekan: Pastikan halaman di-redirect dan membawa pesan sukses
        $response->assertRedirect();
        $response->assertSessionHas('success');

        // 4. Pengecekan Database: Pastikan 'Data Mapper' di Controller bekerja dengan benar!
        // Di sini kita memverifikasi bahwa `product_name` tersimpan ke kolom `name`, dll.
        $this->assertDatabaseHas('products', [
            'product_id' => 'T001',
            'name' => 'Produk Hasil Automasi',
            'type' => 'FG',
            'category' => $category->id,
            'description' => 'Deskripsi untuk testing',
        ]);
    }

    /**
     * Test keamanan: Pastikan form menolak jika ada data yang dikosongkan
     */
    public function test_add_product_validation_fails_if_fields_are_empty()
    {
        // 1. Aksi: Kirim form kosong melompong
        $response = $this->post('/product/add', []);

        // 2. Pengecekan: Pastikan form dikembalikan dengan pesan error untuk kolom wajib
        $response->assertSessionHasErrors(['product_id', 'product_name', 'product_type', 'product_category']);
    }
}
