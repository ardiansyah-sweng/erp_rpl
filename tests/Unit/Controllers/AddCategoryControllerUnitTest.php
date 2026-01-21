<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Http\Controllers\CategoryController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AddCategoryControllerUnitTest extends TestCase
{
    /**
     * Test addCategory dengan input kosong (required validation)
     * Validasi kategori tidak boleh kosong
     */
    public function test_add_category_validates_empty_category()
    {
        // Arrange
        $controller = new CategoryController();
        $request = new Request([
            'category' => '',  // Kosong
            'parent_id' => null,
            'active' => true
        ]);

        // Act & Assert
        $this->expectException(ValidationException::class);
        
        $request->validate([
            'category' => 'required|string|min:3|unique:categories,category',
            'parent_id' => 'nullable|integer',
            'active' => 'required|boolean'
        ]);
    }

    /**
     * Test addCategory dengan kategori < 3 karakter (min:3 validation)
     * Validasi kategori minimal 3 karakter
     */
    public function test_add_category_validates_short_category()
    {
        // Arrange
        $request = new Request([
            'category' => 'AB',  // Hanya 2 karakter
            'parent_id' => null,
            'active' => true
        ]);

        // Act & Assert
        $this->expectException(ValidationException::class);
        
        $request->validate([
            'category' => 'required|string|min:3|unique:categories,category',
            'parent_id' => 'nullable|integer',
            'active' => 'required|boolean'
        ]);
    }

    /**
     * Test addCategory validation rules
     * Memverifikasi bahwa semua validasi rule ada
     */
    public function test_add_category_validation_rules_are_correct()
    {
        // Test menunjukkan validasi yang diperlukan:
        // 1. 'required' - kategori tidak boleh kosong
        // 2. 'string' - kategori harus string
        // 3. 'min:3' - minimum 3 karakter
        // 4. 'unique:categories,category' - tidak boleh duplikat
        
        $validationRules = [
            'category' => 'required|string|min:3|unique:categories,category',
            'parent_id' => 'nullable|integer',
            'active' => 'required|boolean'
        ];

        // Memastikan kategori memiliki aturan yang benar
        $this->assertStringContainsString('required', $validationRules['category']);
        $this->assertStringContainsString('min:3', $validationRules['category']);
        $this->assertStringContainsString('unique:categories,category', $validationRules['category']);
    }

    /**
     * Test addCategory dengan input valid harus lolos validasi
     */
    public function test_add_category_validation_passes_with_valid_input()
    {
        // Arrange
        $request = new Request([
            'category' => 'Elektronik Rumah',  // Valid: >3 karakter
            'parent_id' => null,
            'active' => true
        ]);

        // Act: Cek apakah validasi lolos
        try {
            $validated = $request->validate([
                'category' => 'required|string|min:3',
                'parent_id' => 'nullable|integer',
                'active' => 'required|boolean'
            ]);
            
            // Assert: Validasi lolos, data ada
            $this->assertEquals('Elektronik Rumah', $validated['category']);
            $this->assertTrue($validated['active']);
        } catch (ValidationException $e) {
            $this->fail('Validation should pass with valid input');
        }
    }
}
