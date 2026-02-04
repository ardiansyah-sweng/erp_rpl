<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\Category; 
use App\Constants\ProductColumns; 
use App\Enums\ProductType;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Facades\DB;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_update_product_data_successfully()
    {

        $categoryId = DB::table('categories')->insertGetId([
            'category'   => 'Electronics Test',
            'parent_id'  => null,
            'is_active'  => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        $targetStringId = 'P001'; 
        
        $product = Product::create([
            ProductColumns::PRODUCT_ID => $targetStringId, 
            ProductColumns::NAME       => 'Laptop Jadul',
            ProductColumns::TYPE       => ProductType::FG->value, 
            ProductColumns::CATEGORY   => $categoryId,
            ProductColumns::DESC       => 'Deskripsi lama',
        ]);

        // 2. ACT
        $updatePayload = [
            ProductColumns::NAME => 'Laptop Gaming Baru',
        ];

        $result = Product::updateProduct($product->id, $updatePayload);

        $this->assertNotNull($result, 'Hasil update tidak boleh null');
        $this->assertEquals('Laptop Gaming Baru', $result->name);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            ProductColumns::NAME => 'Laptop Gaming Baru',
        ]);
    }
}