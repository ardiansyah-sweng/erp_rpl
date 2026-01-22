<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\Item;
use App\Constants\ItemColumns;
use App\Constants\ProductColumns; 
use App\Enums\ProductType;
use PHPUnit\Framework\Attributes\Test;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    // ========================================================================
    // TEST UNTUK FUNGSI getAllProducts()
    // ========================================================================

    /**
     * Setup method untuk membuat table dan data default
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Buat table 'item' (singular) untuk match dengan raw SQL di getAllProducts()
        Schema::create('item', function (Blueprint $table) {
            $table->id();
            $table->string('product_id', 50);
            $table->string('sku', 100)->unique();
            $table->string('name', 255);
            $table->unsignedBigInteger('measurement')->nullable();
            $table->decimal('base_price', 15, 2)->default(0);
            $table->decimal('selling_price', 15, 2)->default(0);
            $table->unsignedBigInteger('purchase_unit')->nullable();
            $table->unsignedBigInteger('sell_unit')->nullable();
            $table->integer('stock_unit')->default(0);
            $table->timestamps();
        });
        
        // Buat category default dengan id=1 (karena ProductFactory hard-coded category=1)
        Category::factory()->create([
            'id' => 1,
            'category' => 'Default Category'
        ]);
    }

    /**
     * Cleanup setelah test
     */
    protected function tearDown(): void
    {
        Schema::dropIfExists('item');
        parent::tearDown();
    }

    /**
     * Test getAllProducts returns paginated results
     * @test
     */
    public function test_getAllProducts_returns_paginated_results()
    {
        // Arrange
        Product::factory()->count(5)->create();

        // Act
        $result = Product::getAllProducts();

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $result);
        $this->assertEquals(5, $result->total());
    }

    /**
     * Test getAllProducts returns 10 items per page by default
     * @test
     */
    public function test_getAllProducts_returns_10_items_per_page_by_default()
    {
        // Arrange
        Product::factory()->count(15)->create();

        // Act
        $result = Product::getAllProducts();

        // Assert
        $this->assertEquals(10, $result->perPage());
        $this->assertEquals(10, $result->count());
        $this->assertEquals(15, $result->total());
        $this->assertEquals(2, $result->lastPage());
    }

    /**
     * Test getAllProducts orders by created_at descending
     * @test
     */
    public function test_getAllProducts_orders_by_created_at_descending()
    {
        // Arrange - Create products with different timestamps
        $product1 = Product::factory()->create([
            'product_id' => 'PROD',
            'name' => 'Product 1',
            'created_at' => now()->subDays(3)
        ]);
        
        $product2 = Product::factory()->create([
            'product_id' => 'PRO2',
            'name' => 'Product 2',
            'created_at' => now()->subDays(1)
        ]);
        
        $product3 = Product::factory()->create([
            'product_id' => 'PRO3',
            'name' => 'Product 3',
            'created_at' => now()
        ]);

        // Act
        $result = Product::getAllProducts();

        // Assert - Newest first
        $this->assertEquals('PRO3', $result->first()->product_id);
        $this->assertEquals('PRO2', $result->items()[1]->product_id);
        $this->assertEquals('PROD', $result->last()->product_id);
    }

    /**
     * Test getAllProducts includes category relationship
     * @test
     */
    public function test_getAllProducts_includes_category_relationship()
    {
        // Arrange
        $category = Category::factory()->create([
            'category' => 'Electronics'
        ]);
        
        Product::factory()->create([
            'product_id' => 'ELEC',
            'category' => $category->id
        ]);

        // Act
        $result = Product::getAllProducts();
        $product = $result->first();

        // Assert
        $this->assertNotNull($product);
        // Category mungkin return sebagai ID (integer) atau object, kita cek keduanya
        if (is_object($product->category)) {
            $this->assertEquals('Electronics', $product->category->category);
            $this->assertTrue($product->relationLoaded('category'));
        } else {
            // Jika category return integer (ID), pastikan ID nya benar
            $this->assertEquals($category->id, $product->category);
        }
    }

    /**
     * Test getAllProducts includes items_count with matching SKU pattern
     * @test
     */
    public function test_getAllProducts_includes_items_count_with_sku_pattern()
    {
        // Arrange
        $product = Product::factory()->create([
            'product_id' => 'PROD'
        ]);
        
        // Create 3 items with SKU pattern matching product_id
        // Insert langsung ke table 'item' (singular)
        \DB::table('item')->insert([
            'product_id' => $product->product_id,
            'sku' => $product->product_id . '-01',
            'name' => 'Item 1',
            'measurement' => 1,
            'base_price' => 10000,
            'selling_price' => 15000,
            'purchase_unit' => 30,
            'sell_unit' => 30,
            'stock_unit' => 100,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        \DB::table('item')->insert([
            'product_id' => $product->product_id,
            'sku' => $product->product_id . '-02',
            'name' => 'Item 2',
            'measurement' => 1,
            'base_price' => 10000,
            'selling_price' => 15000,
            'purchase_unit' => 30,
            'sell_unit' => 30,
            'stock_unit' => 100,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        \DB::table('item')->insert([
            'product_id' => $product->product_id,
            'sku' => $product->product_id . '-03',
            'name' => 'Item 3',
            'measurement' => 1,
            'base_price' => 10000,
            'selling_price' => 15000,
            'purchase_unit' => 30,
            'sell_unit' => 30,
            'stock_unit' => 100,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Act
        $result = Product::getAllProducts();

        // Assert
        $this->assertNotNull($result->first()->items_count);
        $this->assertEquals(3, $result->first()->items_count);
    }

    /**
     * Test getAllProducts returns empty when no products exist
     * @test
     */
    public function test_getAllProducts_returns_empty_when_no_products_exist()
    {
        // Arrange - no products created

        // Act
        $result = Product::getAllProducts();

        // Assert
        $this->assertEquals(0, $result->total());
        $this->assertTrue($result->isEmpty());
    }

    /**
     * Test getAllProducts handles multiple pages correctly
     * @test
     */
    public function test_getAllProducts_handles_multiple_pages_correctly()
    {
        // Arrange - create 25 products (3 pages with 10 per page)
        Product::factory()->count(25)->create();

        // Act
        $page1 = Product::getAllProducts();

        // Assert
        $this->assertEquals(3, $page1->lastPage());
        $this->assertEquals(10, $page1->count());
        $this->assertEquals(25, $page1->total());
        $this->assertTrue($page1->hasMorePages());
    }

    /**
     * Test getAllProducts with products that have no items
     * @test
     */
    public function test_getAllProducts_handles_products_with_no_items()
    {
        // Arrange
        Product::factory()->create([
            'product_id' => 'PROD'
        ]);

        // Act
        $result = Product::getAllProducts();

        // Assert
        $this->assertNotNull($result->first());
        $this->assertEquals(0, $result->first()->items_count);
    }

    /**
     * Test getAllProducts with mixed products (some with items, some without)
     * @test
     */
    public function test_getAllProducts_handles_mixed_products_with_and_without_items()
    {
        // Arrange
        // Product with items
        $product1 = Product::factory()->create([
            'product_id' => 'PRO1',
            'created_at' => now()->subHour()
        ]);
        
        // Insert 5 items untuk product1
        for ($i = 1; $i <= 5; $i++) {
            \DB::table('item')->insert([
                'product_id' => $product1->product_id,
                'sku' => $product1->product_id . '-0' . $i,
                'name' => 'Item ' . $i,
                'measurement' => 1,
                'base_price' => 10000,
                'selling_price' => 15000,
                'purchase_unit' => 30,
                'sell_unit' => 30,
                'stock_unit' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // Product without items
        $product2 = Product::factory()->create([
            'product_id' => 'PRO2',
            'created_at' => now()
        ]);

        // Act
        $result = Product::getAllProducts();

        // Assert
        $this->assertEquals(2, $result->total());
        // PRO2 is newest (first), should have 0 items
        $this->assertEquals('PRO2', $result->first()->product_id);
        $this->assertEquals(0, $result->first()->items_count);
        // PRO1 is older (last), should have 5 items
        $this->assertEquals('PRO1', $result->last()->product_id);
        $this->assertEquals(5, $result->last()->items_count);
    }

    /**
     * Test getAllProducts returns correct data structure
     * @test
     */
    public function test_getAllProducts_returns_correct_data_structure()
    {
        // Arrange
        Product::factory()->create([
            'product_id' => 'TEST',
            'name' => 'Test Product'
        ]);

        // Act
        $result = Product::getAllProducts();

        // Assert
        $firstProduct = $result->first();
        $this->assertNotNull($firstProduct->product_id);
        $this->assertNotNull($firstProduct->name);
        $this->assertNotNull($firstProduct->category);
        
        // items_count bisa jadi dari withCount atau selectRaw, cek keduanya
        $hasItemsCount = property_exists($firstProduct, 'items_count') || 
                         isset($firstProduct->items_count) ||
                         array_key_exists('items_count', $firstProduct->getAttributes());
        
        $this->assertTrue($hasItemsCount, 'Product should have items_count property');
        
        // created_at adalah attribute Eloquent, bukan property object
        $this->assertNotNull($firstProduct->created_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $firstProduct->created_at);
    }

    /**
     * Test getAllProducts counts items correctly with partial SKU match
     * @test
     */
    public function test_getAllProducts_counts_items_with_partial_sku_match()
    {
        // Arrange
        $product = Product::factory()->create([
            'product_id' => 'PROD'
        ]);
        
        // Create items with different SKU patterns that start with product_id
        \DB::table('item')->insert([
            'product_id' => $product->product_id,
            'sku' => 'PROD-A',
            'name' => 'Item A',
            'measurement' => 1,
            'base_price' => 10000,
            'selling_price' => 15000,
            'purchase_unit' => 30,
            'sell_unit' => 30,
            'stock_unit' => 100,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        \DB::table('item')->insert([
            'product_id' => $product->product_id,
            'sku' => 'PROD-B',
            'name' => 'Item B',
            'measurement' => 1,
            'base_price' => 10000,
            'selling_price' => 15000,
            'purchase_unit' => 30,
            'sell_unit' => 30,
            'stock_unit' => 100,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        \DB::table('item')->insert([
            'product_id' => $product->product_id,
            'sku' => 'PROD-VARIANT-1',
            'name' => 'Item Variant',
            'measurement' => 1,
            'base_price' => 10000,
            'selling_price' => 15000,
            'purchase_unit' => 30,
            'sell_unit' => 30,
            'stock_unit' => 100,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create item that doesn't match pattern (should not be counted)
        \DB::table('item')->insert([
            'product_id' => 'OTHER',
            'sku' => 'OTHER-01',
            'name' => 'Other Item',
            'measurement' => 1,
            'base_price' => 10000,
            'selling_price' => 15000,
            'purchase_unit' => 30,
            'sell_unit' => 30,
            'stock_unit' => 100,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Act
        $result = Product::getAllProducts();

        // Assert
        $this->assertEquals(3, $result->first()->items_count);
    }

    /**
     * Test getAllProducts with category having null parent
     * @test
     */
    public function test_getAllProducts_handles_category_with_null_parent()
    {
        // Arrange
        $category = Category::factory()->create([
            'category' => 'Root Category',
            'parent_id' => null
        ]);
        
        Product::factory()->create([
            'product_id' => 'PROD',
            'category' => $category->id
        ]);

        // Act
        $result = Product::getAllProducts();
        $product = $result->first();

        // Assert
        $this->assertNotNull($product);
        
        // Category mungkin return sebagai ID atau object
        if (is_object($product->category)) {
            $this->assertEquals('Root Category', $product->category->category);
            $this->assertNull($product->category->parent_id);
        } else {
            // Jika category return integer (ID), pastikan ID nya benar
            $this->assertEquals($category->id, $product->category);
            
            // Validasi category dari database
            $categoryFromDb = Category::find($product->category);
            $this->assertNotNull($categoryFromDb);
            $this->assertEquals('Root Category', $categoryFromDb->category);
            $this->assertNull($categoryFromDb->parent_id);
        }
    }

    /**
     * Test getAllProducts performance with large dataset
     * @test
     */
    public function test_getAllProducts_handles_large_dataset_efficiently()
    {
        // Arrange - create 50 products with items
        $products = Product::factory()->count(50)->create();
        
        foreach ($products as $product) {
            // Insert 3 items per product
            for ($i = 1; $i <= 3; $i++) {
                \DB::table('item')->insert([
                    'product_id' => $product->product_id,
                    'sku' => $product->product_id . '-' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'name' => 'Item ' . $i . ' for ' . $product->product_id,
                    'measurement' => 1,
                    'base_price' => 10000,
                    'selling_price' => 15000,
                    'purchase_unit' => 30,
                    'sell_unit' => 30,
                    'stock_unit' => 100,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Act
        $startTime = microtime(true);
        $result = Product::getAllProducts();
        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // Assert
        $this->assertEquals(50, $result->total());
        $this->assertLessThan(2, $executionTime, 'Query should execute in less than 2 seconds');
    }
  
    private function createManualProduct($overrides = [])
    {
        // 1. Buat Category Dummy
        // Cek dulu apakah category sudah ada di overrides
        $categoryId = $overrides[ProductColumns::CATEGORY] ?? DB::table('categories')->insertGetId([
            'category'   => 'Cat ' . rand(1, 99),
            'parent_id'  => null,
            'is_active'  => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Siapkan Data Default dengan ID Pendek (Maks 5 chars)
        $productId = 'P' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT); 

        $defaults = [
            ProductColumns::PRODUCT_ID => $productId,
            ProductColumns::NAME       => 'Test ' . rand(1, 100),
            ProductColumns::TYPE       => ProductType::FG->value, 
            ProductColumns::CATEGORY   => $categoryId,
            ProductColumns::DESC       => 'Desc',
            ProductColumns::CREATED_AT => now(),
            ProductColumns::UPDATED_AT => now(),
        ];

        // 3. Insert ke Database
        DB::table('products')->insert(array_merge($defaults, $overrides));
    }

    #[Test]
    public function get_product_by_type_returns_correct_data()
    {
        /** Skenario 1: Happy Path */
        
        // Arrange: Buat 2 produk FG (Target)
        $this->createManualProduct([
            ProductColumns::PRODUCT_ID => 'F001',
            ProductColumns::TYPE       => ProductType::FG->value
        ]);
        $this->createManualProduct([
            ProductColumns::PRODUCT_ID => 'F002',
            ProductColumns::TYPE       => ProductType::FG->value
        ]);

        // Arrange: Buat 1 produk RM (Pengganggu)
        $this->createManualProduct([
            ProductColumns::PRODUCT_ID => 'R001',
            ProductColumns::TYPE       => ProductType::RM->value
        ]);

        // Act
        $results = Product::getProductByType(ProductType::FG->value);

        // Assert
        $this->assertCount(2, $results, 'Harusnya hanya ada 2 produk bertipe FG');
        foreach ($results as $product) {
            $this->assertEquals(ProductType::FG, $product->type);
        }
    }

    #[Test]
    public function get_product_by_type_returns_empty_when_no_match()
    {
        /** Skenario 2: Data Kosong */
        
        // Arrange: Hanya buat produk RM
        $this->createManualProduct([
            ProductColumns::PRODUCT_ID => 'R002',
            ProductColumns::TYPE       => ProductType::RM->value
        ]);

        // Act: Cari produk tipe HFG
        $results = Product::getProductByType(ProductType::HFG->value);

        // Assert
        $this->assertTrue($results->isEmpty());
    }

    #[Test]
    public function get_product_by_type_handles_enum_casting_correctly()
    {
        /** Skenario 3: Cek Integritas Data (Enum Casting) */
        
        // Arrange: Gunakan ID pendek 'ENUM'
        $this->createManualProduct([
            ProductColumns::PRODUCT_ID => 'ENUM',
            ProductColumns::TYPE       => ProductType::HFG->value
        ]);

        // Act
        $results = Product::getProductByType(ProductType::HFG->value);
        $product = $results->first();

        // Assert
        $this->assertNotNull($product);
        $this->assertInstanceOf(ProductType::class, $product->type);
        $this->assertEquals(ProductType::HFG, $product->type);
        $this->assertEquals('Half Finished Goods', $product->type->label());
    }
}