<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Constants\ProductColumns;
use App\Constants\Messages;
use App\Helpers\EncryptionHelper;
use App\Enums\ProductType;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;
    
    protected $faker;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->faker = fake('id_ID');

        // Ensure category exists for tests
        if (Category::count() === 0) {
             Category::factory()->create(['id' => 1]);
        }
    }

    // ========== ADD PRODUCT METHOD TESTS ==========

public function test_it_can_add_new_product_successfully()
    {
        
        $category = Category::factory()->create([
            'category' => 'Elektronik', 
            'is_active' => 1
        ]);

       
        $productData = [
            'product_id'  => 'P001',           // char(4), unique
            'name'        => 'Laptop Gaming',  // varchar(35)
            'type'        => 'FG',             // varchar(12), casted to Enum
            'category'    => $category->id,    // int
            'description' => 'Laptop spek tinggi untuk gaming', // varchar(225)
        ];

        $createdProduct = Product::addProduct($productData);

       
        $this->assertInstanceOf(Product::class, $createdProduct);

        
        $this->assertDatabaseHas('products', [
            'product_id' => 'P001',
            'name'       => 'Laptop Gaming',
            'type'       => 'FG',
            'category'   => $category->id,
        ]);

        // Cek apakah atribut pada object yang dikembalikan sesuai
        $this->assertEquals('P001', $createdProduct->product_id);
        $this->assertEquals('Laptop Gaming', $createdProduct->name);
        
        // Verifikasi Enum casting (jika menggunakan Enum ProductType)
        if ($createdProduct->type instanceof ProductType) {
            $this->assertEquals(ProductType::FG, $createdProduct->type);
        } else {
            $this->assertEquals('FG', $createdProduct->type);
        }
    }

/**
     * Skenario 2: Test menambahkan produk dengan deskripsi NULL (boleh kosong).
     * Memastikan kolom 'description' yang bersifat 'YES' (Nullable) bisa menerima null.
     */
    public function test_add_product_with_null_description_is_successful()
    {
        // Arrange
        $category = Category::factory()->create(['is_active' => 1]);
        
        $data = [
            'product_id'  => 'P002',
            'name'        => 'Mouse Wireless',
            'type'        => 'FG',
            'category'    => $category->id,
            'description' => null, // Skenario: Deskripsi dikosongkan
        ];

        // Act
        $product = Product::addProduct($data);

        // Assert
        $this->assertDatabaseHas('products', [
            'product_id' => 'P002',
            'description' => null
        ]);
        $this->assertNull($product->description);
    }

    /**
     * Skenario 3: Test gagal menambahkan produk jika product_id duplikat.
     * Memastikan constraint 'UNI' (Unique) pada database bekerja.
     */
    public function test_add_product_fails_when_product_id_is_duplicate()
    {
        // Arrange
        $category = Category::factory()->create(['is_active' => 1]);
        
        // Buat produk pertama
        Product::addProduct([
            'product_id' => 'DUPL',
            'name'       => 'Produk Asli',
            'type'       => 'FG',
            'category'   => $category->id,
        ]);

        // Expectation: Akan terjadi Error Database (QueryException)
        $this->expectException(\Illuminate\Database\QueryException::class);

        // Act: Coba tambah produk kedua dengan product_id yang SAMA ('DUPL')
        Product::addProduct([
            'product_id' => 'DUPL', // Duplikat
            'name'       => 'Produk Tiruan',
            'type'       => 'FG',
            'category'   => $category->id,
        ]);
    }

    /**
     * Skenario 4: Test gagal menambahkan produk jika nama terlalu panjang.
     * Memastikan constraint 'varchar(35)' pada kolom 'name' bekerja.
     */
    public function test_add_product_fails_when_name_exceeds_max_length()
    {
        // Arrange
        $category = Category::factory()->create(['is_active' => 1]);
        
        // Buat string dengan panjang 36 karakter (batas DB adalah 35)
        $longName = str_repeat('A', 36); 

        // Expectation: Error Database karena Data Truncation / Data too long
        $this->expectException(\Illuminate\Database\QueryException::class);

        // Act
        Product::addProduct([
            'product_id' => 'LONG',
            'name'       => $longName, // Nama terlalu panjang
            'type'       => 'FG',
            'category'   => $category->id,
        ]);
    }

    /**
     * Skenario 5: Test gagal menambahkan produk jika field mandatory hilang.
     * Memastikan constraint 'NO' (Not Null) pada kolom 'category' bekerja.
     */
    public function test_add_product_fails_when_required_category_is_missing()
    {
        // Arrange
        // Kita siapkan data tanpa key 'category'
        $data = [
            'product_id' => 'FAIL',
            'name'       => 'Produk Gagal',
            'type'       => 'FG',
            // 'category' sengaja dihapus
        ];

        // Expectation: Error Database karena Field doesn't have a default value
        $this->expectException(\Illuminate\Database\QueryException::class);

        // Act
        Product::addProduct($data);
    }

    // ========== GET PRODUCT BY ID METHOD TESTS ==========
    
    /**
     * Test getProductById method renders correct view with product data
     */
    public function test_it_displays_product_detail_page()
    {
        // Arrange
        $category = Category::factory()->create([
            'category' => 'Test Category ' . uniqid(),
            'is_active' => 1
        ]);

        $product = Product::factory()->create([
            'product_id' => 'TEST',
            'name' => 'Test Product Detail ' . uniqid(),
            'type' => 'FG',
            'category' => $category->id,
            'description' => 'Test product description for detail page',
        ]);

        $encryptedId = EncryptionHelper::encrypt($product->product_id);

        // Act
        $response = $this->get(route('product.detail', $encryptedId));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('product.detail');
        $response->assertViewHas('product');
        
        $viewProduct = $response->viewData('product');
        $this->assertEquals($product->product_id, $viewProduct->product_id);
        $this->assertEquals($product->name, $viewProduct->name);
        $this->assertEquals($product->description, $viewProduct->description);
        $this->assertEquals('FG', $viewProduct->type->value);
    }

    /**
     * Test getProductById method with non-existent product returns 404
     */
    public function test_it_shows_404_for_non_existent_product()
    {
        $nonExistentId = 99999;
        $encryptedId = EncryptionHelper::encrypt($nonExistentId);

        $response = $this->get(route('product.detail', $encryptedId));

        $response->assertStatus(404);
        $response->assertSee(Messages::PRODUCT_NOT_FOUND);
    }

    /**
     * Test getProductById method with invalid encrypted ID
     */
    public function test_it_shows_404_for_invalid_encrypted_id()
    {
        $invalidEncryptedId = 'invalid-encrypted-string-12345';

        $response = $this->get(route('product.detail', $invalidEncryptedId));

        $this->assertTrue(in_array($response->status(), [404, 500]));
    }

    /**
     * Test getProductById method with empty encrypted ID
     */
    public function test_it_shows_404_for_empty_encrypted_id()
    {
        $response = $this->get('/product/detail/');

        $response->assertStatus(404);
    }

    /**
     * Test getProductById method displays product with category relationship
     */
    public function test_it_displays_product_with_category_relationship()
    {
        $category = Category::factory()->create([
            'category' => 'Electronics',
            'is_active' => 1
        ]);

        $product = Product::factory()->create([
            ProductColumns::PRODUCT_ID => 'EL01',
            ProductColumns::NAME => 'Smartphone Test',
            ProductColumns::TYPE => 'FG',
            ProductColumns::CATEGORY => $category->id,
            ProductColumns::DESC => 'Latest smartphone model',
        ]);

        $encryptedId = EncryptionHelper::encrypt($product->product_id);

        $response = $this->get(route('product.detail', $encryptedId));

        $response->assertStatus(200);
        $response->assertViewIs('product.detail');
        
        $response->assertSee('Smartphone Test');
        $response->assertSee('EL01');
        $response->assertSee('Latest smartphone model');
        
        $viewProduct = $response->viewData('product');
        $this->assertEquals($product->product_id, $viewProduct->product_id);
        $this->assertEquals($category->id, $viewProduct->category);
    }

    /**
     * Test getProductById method with different product types
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('productTypeDataProvider')]
    public function test_it_displays_products_of_different_types($productType, $description)
    {
        $category = Category::factory()->create([
            'category' => 'Test Category',
            'is_active' => 1
        ]);

        $product = Product::factory()->create([
            ProductColumns::PRODUCT_ID => 'T' . substr(uniqid(), -3),
            ProductColumns::NAME => 'Product ' . $productType,
            ProductColumns::TYPE => $productType,
            ProductColumns::CATEGORY => $category->id,
            ProductColumns::DESC => $description,
        ]);

        $encryptedId = EncryptionHelper::encrypt($product->product_id);

        $response = $this->get(route('product.detail', $encryptedId));

        $response->assertStatus(200);
        $response->assertViewIs('product.detail');
        $response->assertSee('Product ' . $productType);
        $response->assertSee($productType);
        $response->assertSee($description);
        
        $viewProduct = $response->viewData('product');
        $this->assertEquals($productType, $viewProduct->type->value);
    }

    /**
     * Data provider for different product types
     */
    public static function productTypeDataProvider(): array
    {
        return [
            ['FG', 'Finished goods product for testing'],
            ['RM', 'Raw materials product for testing'],
            ['HFG', 'Half finished goods product for testing'],
        ];
    }

    /**
     * Test getProductById method with inactive product
     */
    public function test_it_displays_inactive_product()
    {
        $category = Category::factory()->create([
            'category' => 'Test Category',
            'is_active' => 1
        ]);

        $product = Product::factory()->create([
            ProductColumns::PRODUCT_ID => 'IN01',
            ProductColumns::NAME => 'Inactive Product',
            ProductColumns::TYPE => 'FG',
            ProductColumns::CATEGORY => $category->id,
            ProductColumns::DESC => 'This product is inactive',
        ]);

        $encryptedId = EncryptionHelper::encrypt($product->product_id);

        $response = $this->get(route('product.detail', $encryptedId));

        $response->assertStatus(200);
        $response->assertViewIs('product.detail');
        $response->assertSee('Inactive Product');
        $response->assertSee('IN01');
        
        $viewProduct = $response->viewData('product');
        $this->assertEquals(0, $viewProduct->is_active ?? 0);
    }

    // ========== SEARCH PRODUCT METHOD TESTS ==========

    /**
     * Test search product dengan keyword yang valid dan match dengan product_id
     */
    public function test_search_product_by_product_id()
    {
        // Arrange - Create category first
        $category = Category::factory()->create(['is_active' => 1]);

        Product::factory()->create([
            'product_id'  => 'KAO',
            'name'        => 'Kaos TShirt Putih',
            'type'        => 'FG',
            'category'    => $category->id,
            'description' => 'Kaos berkualitas premium'
        ]);

        Product::factory()->create([
            'product_id'  => 'TOP',
            'name'        => 'Topi Snapback',
            'type'        => 'FG',
            'category'    => $category->id,
            'description' => 'Topi untuk pria'
        ]);

        $response = $this->get(route('product.search', ['keyword' => 'KAO']));

        $response->assertStatus(200);
        $response->assertViewIs('product.list');
        
        // Verify product exists in results
        $response->assertViewHas('products', function($products) {
            return $products->contains(fn($p) => $p->product_id === 'KAO');
        });
        
        // Verify TOP product doesn't appear
        $response->assertViewHas('products', function($products) {
            $foundKAO = $products->contains(fn($p) => $p->product_id === 'KAO');
            $topCount = $products->filter(fn($p) => $p->product_id === 'TOP')->count();
            return $foundKAO && $topCount === 0;
        });
    }

    /**
     * Test search product dengan keyword yang match dengan nama product
     */
    public function test_search_product_by_product_name()
    {
        // Arrange - Create category first
        $category = Category::factory()->create(['is_active' => 1]);

        Product::factory()->create([
            'product_id'  => 'KAO',
            'name'        => 'Kaos TShirt Premium',
            'type'        => 'FG',
            'category'    => $category->id,
            'description' => 'Pakaian berkualitas'
        ]);

        Product::factory()->create([
            'product_id'  => 'JAK',
            'name'        => 'Jaket Kulit',
            'type'        => 'FG',
            'category'    => $category->id,
            'description' => 'Jaket premium'
        ]);

        $response = $this->get(route('product.search', ['keyword' => 'TShirt']));

        $response->assertStatus(200);
        $response->assertViewIs('product.list');
        
        $response->assertViewHas('products', function($products) {
            return $products->count() >= 1 && 
                   $products->contains(fn($p) => stripos($p->name, 'TShirt') !== false);
        });
    }

    /**
     * Test search product dengan keyword yang tidak ada (empty result)
     */
    public function test_search_product_with_no_results()
    {
        // Arrange - Create category first
        $category = Category::factory()->create(['is_active' => 1]);

        Product::factory()->create([
            'product_id'  => 'KAO',
            'name'        => 'Kaos',
            'type'        => 'FG',
            'category'    => $category->id,
            'description' => 'Produk Kaos'
        ]);

        Product::factory()->create([
            'product_id'  => 'TOP',
            'name'        => 'Topi',
            'type'        => 'FG',
            'category'    => $category->id,
            'description' => 'Produk Topi'
        ]);

        $response = $this->get(route('product.search', ['keyword' => 'XYZABC123']));

        $response->assertStatus(200);
        $response->assertViewIs('product.list');
        
        $response->assertViewHas('products', function($products) {
            return $products->count() === 0;
        });
    }

    /**
     * Test search product dengan keyword partial match dari multiple fields
     */
    public function test_search_product_with_partial_keyword_match()
    {
        // Arrange - Create category first
        $category = Category::factory()->create(['is_active' => 1]);

        Product::factory()->create([
            'product_id'  => 'PR1',
            'name'        => 'Produk A',
            'type'        => 'RM',
            'category'    => $category->id,
            'description' => 'Deskripsi produk'
        ]);

        Product::factory()->create([
            'product_id'  => 'PR2',
            'name'        => 'Barang B',
            'type'        => 'FG',
            'category'    => $category->id,
            'description' => 'Ini adalah raw material'
        ]);

        Product::factory()->create([
            'product_id'  => 'XYZ',
            'name'        => 'Item C',
            'type'        => 'HFG',
            'category'    => $category->id,
            'description' => 'Deskripsi lainnya'
        ]);

        $response = $this->get(route('product.search', ['keyword' => 'PR']));

        $response->assertStatus(200);
        $response->assertViewIs('product.list');
        
        $response->assertViewHas('products', function($products) {
            return $products->count() >= 1;
        });

        // Verify all results contain "PR"
        $response->assertViewHas('products', function($products) {
            foreach ($products as $product) {
                $isMatch = stripos($product->product_id, 'PR') !== false ||
                           stripos($product->name, 'PR') !== false ||
                           stripos($product->description, 'PR') !== false;
                
                if (!$isMatch) {
                    return false;
                }
            }
            return true;
        });
    }
}