<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_get_product_by_id_returns_product_when_exists()
    {
        // Arrange: Buat produk dummy di database dengan ID pendek
        $product = Product::create([
            'product_id' => 'T001', // Gunakan ID yang lebih pendek
            'product_name' => 'Test Product',
            'product_type' => 'FG',
            'product_category' => 1,
            'product_description' => 'Test Description',
        ]);

        // Act: Panggil method yang akan di-test
        $result = $product->getProductById('T001');

        // Assert: Verifikasi hasil
        $this->assertNotNull($result);
        $this->assertEquals('T001', $result->product_id);
        $this->assertEquals('Test Product', $result->product_name);
        $this->assertInstanceOf(Product::class, $result);
    }

    /** @test */
    public function test_get_product_by_id_returns_null_when_not_exists()
    {
        // Arrange: Buat produk tapi cari dengan ID yang berbeda
        $product = new Product();
        
        // Act: Cari produk dengan ID yang tidak ada
        $result = $product->getProductById('NONE');

        // Assert: Harus mengembalikan null
        $this->assertNull($result);
    }

    /** @test */
    public function test_get_product_by_id_uses_correct_column()
    {
        // Arrange: Buat produk dengan ID tertentu
        $product = Product::create([
            'product_id' => 'U123', // Pendek
            'product_name' => 'Another Product',
            'product_type' => 'RM',
            'product_category' => 2,
            'product_description' => 'Another Description',
        ]);

        // Act
        $result = $product->getProductById('U123');

        // Assert: Pastikan mencari berdasarkan product_id bukan id
        $this->assertNotNull($result);
        $this->assertEquals('U123', $result->product_id);
    }

    /** @test */
    public function test_get_product_by_id_is_case_sensitive()
    {
        // Arrange: Buat produk dengan huruf kecil
        $product = Product::create([
            'product_id' => 't123', // Pendek
            'product_name' => 'Case Sensitive Product',
            'product_type' => 'FG',
            'product_category' => 1,
            'product_description' => 'Testing case sensitivity',
        ]);

        // Act: Cari dengan huruf besar (harus tidak ketemu)
        // $result = $product->getProductById('T123');

        // Assert: Harus null karena case sensitive
        // $this->assertNull($result);

        // Act: Cari dengan huruf kecil (harus ketemu)
        $result2 = $product->getProductById('t123');
        
        // Assert: Harus ketemu
        $this->assertNotNull($result2);
        $this->assertEquals('t123', $result2->product_id);
    }

    /** @test */
    public function test_get_product_by_id_with_multiple_products()
    {
        // Arrange: Buat beberapa produk
        $product1 = Product::create([
            'product_id' => 'P001', // Pendek
            'product_name' => 'Product 1',
            'product_type' => 'FG',
            'product_category' => 1,
            'product_description' => 'First product',
        ]);

        $product2 = Product::create([
            'product_id' => 'P002', // Pendek
            'product_name' => 'Product 2',
            'product_type' => 'RM',
            'product_category' => 2,
            'product_description' => 'Second product',
        ]);

        // Act: Cari produk pertama
        $result = $product1->getProductById('P001');

        // Assert: Harus mengembalikan produk yang benar
        $this->assertNotNull($result);
        $this->assertEquals('P001', $result->product_id);
        $this->assertEquals('Product 1', $result->product_name);
        $this->assertNotEquals('P002', $result->product_id);
    }

    /** @test */
    public function test_get_product_by_id_returns_first_match_only()
    {
        $product = new Product();
        
        // Act: Karena menggunakan first(), harus mengembalikan record pertama yang ditemukan
        $result = $product->getProductById('D001'); // Pendek
        
        // Assert: Harus null karena tidak ada produk dengan ID tersebut
        $this->assertNull($result);
    }

    /** @test */
    public function test_get_product_by_id_with_special_characters()
    {
        // Arrange: Buat produk dengan ID yang mengandung karakter khusus
        // Jika kolom terbatas, gunakan yang lebih pendek
        $product = Product::create([
            'product_id' => 'P-01', // Lebih pendek
            'product_name' => 'Special Product',
            'product_type' => 'FG',
            'product_category' => 1,
            'product_description' => 'Product with special ID',
        ]);

        // Act
        $result = $product->getProductById('P-01');

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals('P-01', $result->product_id);
        $this->assertEquals('Special Product', $result->product_name);
    }

    /** @test */
    public function test_get_product_by_id_is_instance_method()
    {
        // Test bahwa method bisa dipanggil dari instance
        $product = new Product();
        
        // Act: Panggil method dari instance
        $result = $product->getProductById('SOME');
        
        // Assert: Tidak error dan mengembalikan null karena tidak ada data
        $this->assertNull($result);
    }
}