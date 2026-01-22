<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Constants\ProductColumns;

class GetProductByCategoryTest extends TestCase
{
    use WithFaker;
    
    protected $faker;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->faker = fake('id_ID');

        // Create test categories
        $this->category = Category::factory()->create([
            'category' => 'Electronic Gadgets',
            'is_active' => 1
        ]);
    }

    /**
     * Test getProductByCategory returns products for valid category
     */
    public function test_get_products_by_valid_category()
    {
        // Arrange
        $products = Product::factory()->count(3)->create([
            'category' => $this->category->id,
        ]);

        // Act
        $response = $this->get(route('product.byCategory', $this->category->id));

        // Assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'data' => [
                    '*' => [
                        'id',
                        'product_id',
                        'name',
                        'type',
                        'category',
                        'description',
                    ]
                ],
                'total',
                'per_page',
                'current_page',
            ]
        ]);
        
        $response->assertJson([
            'success' => true,
            'message' => 'Produk berdasarkan kategori ditemukan.',
        ]);
        
        $this->assertEquals(3, $response->json()['data']['total']);
    }

    /**
     * Test getProductByCategory returns empty for non-existent category
     */
    public function test_get_products_by_non_existent_category()
    {
        // Act
        $response = $this->get(route('product.byCategory', 9999));

        // Assert
        $response->assertStatus(404);
        $response->assertJson([
            'success' => false,
            'message' => 'Tidak ada produk untuk kategori tersebut.',
        ]);
    }

    /**
     * Test getProductByCategory returns only products from specified category
     */
    public function test_get_products_by_category_returns_only_specified_category_products()
    {
        // Arrange
        $category1 = Category::factory()->create(['category' => 'Category 1']);
        $category2 = Category::factory()->create(['category' => 'Category 2']);

        $products1 = Product::factory()->count(2)->create(['category' => $category1->id]);
        $products2 = Product::factory()->count(3)->create(['category' => $category2->id]);

        // Act
        $response = $this->get(route('product.byCategory', $category1->id));

        // Assert
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
        
        $this->assertEquals(2, $response->json()['data']['total']);
    }

    /**
     * Test getProductByCategory pagination works correctly
     */
    public function test_get_products_by_category_pagination()
    {
        // Arrange - Create 15 products (more than default per_page)
        $products = Product::factory()->count(15)->create([
            'category' => $this->category->id,
        ]);

        // Act
        $response = $this->get(route('product.byCategory', $this->category->id));

        // Assert
        $response->assertStatus(200);
        $this->assertEquals(15, $response->json()['data']['total']);
        $this->assertEquals(10, $response->json()['data']['per_page']); // Default per_page
        $this->assertEquals(1, $response->json()['data']['current_page']);
    }

    /**
     * Test getProductByCategory pagination page 2
     */
    public function test_get_products_by_category_pagination_page_two()
    {
        // Arrange
        $products = Product::factory()->count(15)->create([
            'category' => $this->category->id,
        ]);

        // Act
        $response = $this->get(route('product.byCategory', $this->category->id) . '?page=2');

        // Assert
        $response->assertStatus(200);
        $this->assertEquals(15, $response->json()['data']['total']);
        $this->assertEquals(2, $response->json()['data']['current_page']);
        $this->assertCount(5, $response->json()['data']['data']); // Remaining 5 items on page 2
    }

    /**
     * Test getProductByCategory includes category relationship
     */
    public function test_get_products_by_category_includes_category_data()
    {
        // Arrange
        $product = Product::factory()->create([
            'category' => $this->category->id,
        ]);

        // Act
        $response = $this->get(route('product.byCategory', $this->category->id));

        // Assert
        $response->assertStatus(200);
        
        $returnedProduct = $response->json()['data']['data'][0];
        // Category should be loaded as a relationship object/array
        $this->assertIsArray($returnedProduct['category']);
        $this->assertArrayHasKey('id', $returnedProduct['category']);
        $this->assertEquals($this->category->id, $returnedProduct['category']['id']);
    }

    /**
     * Test getProductByCategory response structure
     */
    public function test_get_products_by_category_response_structure()
    {
        // Arrange
        $product = Product::factory()->create([
            'category' => $this->category->id,
            'name' => 'Test Product',
        ]);

        // Act
        $response = $this->get(route('product.byCategory', $this->category->id));

        // Assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data',
        ]);
        
        $this->assertTrue($response->json()['success']);
        $this->assertIsArray($response->json()['data']);
    }

    /**
     * Test getProductByCategory with zero products returns 404
     */
    public function test_get_products_by_category_zero_products_returns_404()
    {
        // Arrange
        $category = Category::factory()->create();

        // Act
        $response = $this->get(route('product.byCategory', $category->id));

        // Assert
        $response->assertStatus(404);
        $response->assertJson([
            'success' => false,
            'message' => 'Tidak ada produk untuk kategori tersebut.',
        ]);
    }

    /**
     * Test getProductByCategory returns correct product data types
     */
    public function test_get_products_by_category_returns_correct_data_types()
    {
        // Arrange
        $product = Product::factory()->create([
            'category' => $this->category->id,
            'name' => 'Test Product',
        ]);

        // Act
        $response = $this->get(route('product.byCategory', $this->category->id));

        // Assert
        $response->assertStatus(200);
        
        $data = $response->json();
        $this->assertIsBool($data['success']);
        $this->assertIsString($data['message']);
        $this->assertIsArray($data['data']);
    }

    /**
     * Test getProductByCategory orders by created_at descending
     */
    public function test_get_products_by_category_orders_by_created_at_descending()
    {
        // Arrange
        $product1 = Product::factory()->create([
            'category' => $this->category->id,
            'name' => 'Product 1',
            'created_at' => now()->subDays(2),
        ]);
        
        $product2 = Product::factory()->create([
            'category' => $this->category->id,
            'name' => 'Product 2',
            'created_at' => now(),
        ]);

        // Act
        $response = $this->get(route('product.byCategory', $this->category->id));

        // Assert
        $response->assertStatus(200);
        
        $products = $response->json()['data']['data'];
        $this->assertEquals($product2->product_id, $products[0]['product_id']);
        $this->assertEquals($product1->product_id, $products[1]['product_id']);
    }

    /**
     * Test getProductByCategory with string category ID
     */
    public function test_get_products_by_category_with_string_category_id()
    {
        // Act - Use string ID (should be type-juggled to int)
        $response = $this->get(route('product.byCategory', (string)$this->category->id));

        // Assert
        $response->assertStatus(404); // No products created yet
    }

    /**
     * Test getProductByCategory with multiple products returns correct count
     */
    public function test_get_products_by_category_multiple_products_count()
    {
        // Arrange
        $count = 5;
        $products = Product::factory()->count($count)->create([
            'category' => $this->category->id,
        ]);

        // Act
        $response = $this->get(route('product.byCategory', $this->category->id));

        // Assert
        $response->assertStatus(200);
        $this->assertEquals($count, $response->json()['data']['total']);
        $this->assertCount($count, $response->json()['data']['data']);
    }
}
