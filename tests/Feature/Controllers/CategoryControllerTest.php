<?php

namespace Tests\Feature\Controllers;

use App\Models\MeasurementUnit;
use App\Models\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;
use App\Models\User;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    /**
     * Setup yang dijalankan sebelum setiap test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // FIX: Tambahkan data dasar yang mungkin dibutuhkan oleh factory lain.
        // Ini membuat test lebih stabil dan tidak mudah error karena dependensi.
        Category::factory()->create(); // Pastikan ada minimal 1 kategori
        MeasurementUnit::factory()->count(3)->create(); // Pastikan ada measurement unit
    }

    // ===========================================
    // SHOW TESTS - GET /categories/{id}
    // ===========================================

    /** @test */
    public function it_can_display_the_detail_page_for_an_existing_category()
    {
        // Arrange: Buat satu kategori spesifik menggunakan factory.
        $category = Category::factory()->create([
            'category' => 'Elektronik Dapur',
            'is_active' => true,
        ]);

        // Act: Lakukan permintaan GET ke rute 'show' dengan ID kategori tersebut.
        // Asumsi nama rutenya adalah 'categories.show' atau 'categories.detail'.
        // Mari kita gunakan 'categories.show' sebagai standar.
        $response = $this->get(route('categories.show', $category->id));

        // Assert: Verifikasi bahwa respons yang diterima sudah benar.
        $response->assertStatus(200);
        $response->assertViewIs('product.category.detail'); // Pastikan view yang benar ditampilkan.
        $response->assertViewHas('category'); // Pastikan data 'category' dikirim ke view.

        // Assert: Pastikan data yang dikirim ke view adalah data yang kita harapkan.
        $viewCategory = $response->viewData('category');
        $this->assertInstanceOf(Category::class, $viewCategory);
        $this->assertEquals($category->id, $viewCategory->id);
        $this->assertEquals('Elektronik Dapur', $viewCategory->category);
    }

    /** @test */
    public function it_returns_a_404_not_found_error_for_a_non_existent_category()
    {
        // Arrange: Tentukan ID yang tidak ada di database.
        $nonExistentId = 9999;

        // Act: Lakukan permintaan GET ke rute 'show' dengan ID yang tidak ada.
        $response = $this->get(route('categories.show', $nonExistentId));

        // Assert: Pastikan responsnya adalah redirect ke halaman index.
        // Ini sesuai dengan implementasi di controller: `return redirect()->route('categories.index')`
        $response->assertStatus(302);
        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('error');
    }

    // =============================================================
    // DELETETESTS - DELETE /category/delete/{id} (deleteCategory method)
    // =============================================================

    /** @test */
    public function it_can_delete_an_existing_category_via_deleteCategory_method()
    {
        // Arrange: Buat kategori yang akan dihapus.
        $category = Category::factory()->create();

        // Act: Kirim request DELETE ke rute kustom 'category.delete'.
        // Kita simulasikan request datang dari halaman index.
        $response = $this->from(route('categories.index'))
                         ->delete(route('category.delete', $category->id));

        // Assert: Pastikan redirect kembali ke halaman index dengan pesan sukses.
        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('success', 'Kategori berhasil dihapus!');

        // Assert: Pastikan kategori sudah tidak ada di database.
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    /** @test */
    public function it_fails_to_delete_a_non_existent_category_via_deleteCategory_method()
    {
        // Arrange: Siapkan ID yang tidak ada.
        $nonExistentId = 9999;

        // Act: Kirim request DELETE untuk ID yang tidak ada.
        $response = $this->from(route('categories.index'))
                         ->delete(route('category.delete', $nonExistentId));

        // Assert: Pastikan redirect kembali dengan pesan error.
        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('error', 'Kategori tidak ditemukan atau gagal dihapus.');
    }
}
