<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test search category dengan keyword menggunakan parameter 'search' 
     * (sesuai dengan method index() di controller yang menggunakan $request->input('search'))
     */
    public function test_search_category_with_valid_keyword_returns_filtered_results()
    {
        // Arrange: Buat test data menggunakan factory
        Category::factory()->create(['category' => 'Elektronik']);
        Category::factory()->create(['category' => 'Furniture']);
        Category::factory()->create(['category' => 'Makanan']);

        // Act: Lakukan request dengan parameter 'search' ke route /categories
        // (bukan /categories/search yang mungkin belum berfungsi dengan baik)
        $response = $this->get('/categories?search=Elektronik');

        // Assert: Verifikasi response
        $response->assertStatus(200);
        $response->assertViewIs('category.index');
        
        $categories = $response->viewData('categories');
        $this->assertNotEmpty($categories);
        // Seharusnya hanya Elektronik yang muncul
        $this->assertTrue($categories->contains('category', 'Elektronik'));
        $this->assertFalse($categories->contains('category', 'Furniture'));
    }

    /**
     * Test search category dengan partial keyword
     * Mengharapkan hasil yang cocok dengan LIKE query
     */
    public function test_search_category_with_partial_keyword_returns_matching_results()
    {
        // Arrange
        Category::factory()->create(['category' => 'Elektronik']);
        Category::factory()->create(['category' => 'Elektronik Rumah Tangga']);
        Category::factory()->create(['category' => 'Furniture']);

        // Act: Search dengan partial keyword melalui parameter 'search'
        $response = $this->get('/categories?search=Elek');

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('category.index');
        
        $categories = $response->viewData('categories');
        // Seharusnya 2 item (Elektronik dan Elektronik Rumah Tangga)
        $this->assertCount(2, $categories);
        $this->assertTrue($categories->contains('category', 'Elektronik'));
        $this->assertTrue($categories->contains('category', 'Elektronik Rumah Tangga'));
    }

    /**
     * Test search category dengan keyword yang tidak ditemukan
     * Mengharapkan empty collection
     */
    public function test_search_category_with_non_matching_keyword_returns_empty()
    {
        // Arrange
        Category::factory()->create(['category' => 'Elektronik']);
        Category::factory()->create(['category' => 'Furniture']);

        // Act: Search dengan keyword yang tidak ada
        $response = $this->get('/categories?search=Otomotif');

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('category.index');
        
        $categories = $response->viewData('categories');
        // Seharusnya tidak ada hasil
        $this->assertEmpty($categories);
    }

    /**
     * Test index category tanpa parameter search
     * Mengharapkan semua kategori dikembalikan dengan pagination
     */
    public function test_index_category_without_keyword_returns_all_categories()
    {
        // Arrange: Buat 3 kategori
        Category::factory(3)->create();

        // Act: Request ke /categories tanpa parameter search
        $response = $this->get('/categories');

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('category.index');
        
        $categories = $response->viewData('categories');
        // Seharusnya mengembalikan semua kategori (dengan pagination)
        $this->assertNotEmpty($categories);
        $this->assertGreaterThanOrEqual(3, $categories->total());
    }
    
}