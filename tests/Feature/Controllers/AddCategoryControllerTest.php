<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class AddCategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test addCategory dengan input kosong (required validation)
     * Validasi kategori tidak boleh kosong
     */
    public function test_add_category_with_empty_input_fails_validation()
    {
        // Arrange: Siapkan data yang kosong
        $data = [
            'category' => '',  // Kategori kosong
            'parent_id' => null,
            'active' => true
        ];

        // Act: Kirim request POST ke route addCategory
        $response = $this->post('/category/add', $data);

        // Assert: Verifikasi bahwa request gagal dengan error validation
        $response->assertSessionHasErrors('category');
        $errors = $response->session()->get('errors')->toArray();
        $this->assertTrue(isset($errors['category']));
    }

    /**
     * Test addCategory dengan kategori kurang dari 3 karakter (min:3 validation)
     * Validasi kategori minimal harus 3 karakter
     */
    public function test_add_category_with_less_than_three_characters_fails_validation()
    {
        // Arrange: Siapkan data dengan kategori kurang dari 3 karakter
        $data = [
            'category' => 'AB',  // Hanya 2 karakter
            'parent_id' => null,
            'active' => true
        ];

        // Act: Kirim request POST ke route addCategory
        $response = $this->post('/category/add', $data);

        // Assert: Verifikasi bahwa request gagal dengan error validation
        $response->assertSessionHasErrors('category');
        $errors = $response->session()->get('errors')->toArray();
        $this->assertTrue(isset($errors['category']));
    }

    /**
     * Test addCategory dengan kategori yang sudah ada (unique validation)
     * Validasi kategori tidak boleh duplikat
     */
    public function test_add_category_with_existing_category_fails_validation()
    {
        // Arrange: Buat kategori yang sudah ada
        DB::table('categories')->insert([
            'category' => 'Elektronik',
            'parent_id' => 0,
            'active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Siapkan data dengan kategori yang sudah ada
        $data = [
            'category' => 'Elektronik',  // Kategori sudah ada
            'parent_id' => null,
            'active' => true
        ];

        // Act: Kirim request POST ke route addCategory dengan kategori duplikat
        $response = $this->post('/category/add', $data);

        // Assert: Verifikasi bahwa request gagal dengan error validation
        $response->assertSessionHasErrors('category');
        $errors = $response->session()->get('errors')->toArray();
        $this->assertTrue(isset($errors['category']));
    }

    /**
     * Test addCategory dengan input valid (semua validasi lolos)
     * Validasi kategori berhasil ditambahkan
     */
    public function test_add_category_with_valid_input_succeeds()
    {
        // Arrange: Siapkan data yang valid
        $data = [
            'category' => 'Elektronik Rumah Tangga',  // Valid: > 3 karakter
            'parent_id' => null,
            'active' => true
        ];

        // Act: Kirim request POST ke route addCategory
        $response = $this->post('/category/add', $data);

        // Assert: Verifikasi bahwa request berhasil redirect dengan success message
        $response->assertRedirect(route('category.list'));
        $response->assertSessionHas('success', 'Kategori berhasil ditambahkan!');
        
        // Verifikasi bahwa kategori tersimpan di database
        $this->assertDatabaseHas('categories', [
            'category' => 'Elektronik Rumah Tangga',
            'parent_id' => 0,
            'active' => true
        ]);
    }
}

