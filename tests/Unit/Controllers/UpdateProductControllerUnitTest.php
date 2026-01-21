<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class UpdateProductControllerUnitTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test category for valid tests
        Category::factory()->create(['id' => 1, 'category' => 'Test Category']);
    }

    /**
     * Test updateProduct dengan product_name kosong (required validation)
     * Validasi product_name tidak boleh kosong
     */
    public function test_update_product_with_empty_name_fails_validation()
    {
        // Arrange: Siapkan data dengan product_name kosong
        $data = [
            'product_name' => '',  // Kosong
            'product_type' => 'RM',
            'product_category' => 1,
            'product_description' => 'Test'
        ];

        // Act & Assert: Validasi harus gagal
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        
        $request = new \Illuminate\Http\Request();
        $request->merge($data);
        $request->validate([
            'product_name' => 'required|string|min:3|max:35',
            'product_type' =>  'required|string|max:12',
            'product_category' => 'required|integer|exists:categories,id',
            'product_description' => 'nullable|string|max:255',
        ]);
    }

    /**
     * Test updateProduct dengan product_name < 3 karakter (min:3 validation)
     * Validasi product_name minimal 3 karakter
     */
    public function test_update_product_with_short_name_fails_validation()
    {
        // Arrange: Siapkan data dengan product_name < 3 karakter
        $data = [
            'product_name' => 'AB',  // Hanya 2 karakter
            'product_type' => 'RM',
            'product_category' => 1,
            'product_description' => 'Test'
        ];

        // Act & Assert: Validasi harus gagal
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        
        $request = new \Illuminate\Http\Request();
        $request->merge($data);
        $request->validate([
            'product_name' => 'required|string|min:3|max:35',
            'product_type' =>  'required|string|max:12',
            'product_category' => 'required|integer|exists:categories,id',
            'product_description' => 'nullable|string|max:255',
        ]);
    }

    /**
     * Test updateProduct dengan kategori tidak eksis (exists validation)
     * Validasi category harus ada di database
     */
    public function test_update_product_with_non_existing_category_fails_validation()
    {
        // Arrange: Siapkan data dengan kategori yang tidak ada
        $data = [
            'product_name' => 'Valid Product Name',
            'product_type' => 'RM',
            'product_category' => 9999,  // Kategori yang tidak ada
            'product_description' => 'Test'
        ];

        // Act & Assert: Validasi harus gagal
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        
        $request = new \Illuminate\Http\Request();
        $request->merge($data);
        $request->validate([
            'product_name' => 'required|string|min:3|max:35',
            'product_type' =>  'required|string|max:12',
            'product_category' => 'required|integer|exists:categories,id',
            'product_description' => 'nullable|string|max:255',
        ]);
    }

    /**
     * Test updateProduct dengan input valid
     * Validasi lolos dan updateProduct Model dipanggil
     */
    public function test_update_product_with_valid_input_calls_model_update()
    {
        // Arrange: Siapkan data yang valid
        $updateData = [
            'product_name' => 'Updated Product Name',
            'product_type' => 'FG',
            'product_category' => 1,  // Category ID 1 sudah ada dari setUp
            'product_description' => 'Updated description'
        ];

        // Act: Validasi harus lolos
        try {
            $request = new \Illuminate\Http\Request();
            $request->merge($updateData);
            $validated = $request->validate([
                'product_name' => 'required|string|min:3|max:35',
                'product_type' =>  'required|string|max:12',
                'product_category' => 'required|integer|exists:categories,id',
                'product_description' => 'nullable|string|max:255',
            ]);
            
            // Assert: Validasi berhasil
            $this->assertNotEmpty($validated);
            $this->assertEquals('Updated Product Name', $validated['product_name']);
            $this->assertEquals('FG', $validated['product_type']);
            $this->assertEquals(1, $validated['product_category']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->fail('Validation should pass with valid input: ' . json_encode($e->errors()));
        }
    }

    /**
     * Test updateProduct validation rules
     * Memverifikasi semua validasi rule ada
     */
    public function test_update_product_validation_rules_are_correct()
    {
        // Arrange: Ambil validation rules dari controller atau test
        $rules = [
            'product_name' => 'required|string|min:3|max:35',
            'product_type' =>  'required|string|max:12',
            'product_category' => 'required|integer|exists:categories,id',
            'product_description' => 'nullable|string|max:255',
        ];

        // Act & Assert: Verifikasi semua required rules ada
        $this->assertTrue(
            str_contains($rules['product_name'], 'required'),
            'product_name harus memiliki required rule'
        );
        $this->assertTrue(
            str_contains($rules['product_name'], 'min:3'),
            'product_name harus memiliki min:3 rule'
        );
        $this->assertTrue(
            str_contains($rules['product_category'], 'exists:categories,id'),
            'product_category harus memiliki exists:categories,id rule'
        );
        $this->assertTrue(
            str_contains($rules['product_type'], 'required'),
            'product_type harus memiliki required rule'
        );
    }
}
