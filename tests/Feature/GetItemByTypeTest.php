<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Product;
use App\Enums\ProductType; // pastikan enum yang digunakan

class GetItemByTypeTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        Route::get('api/items', function () {
            $type = request()->query('type');
            $items = DB::table('items')
                ->join('products', 'items.product_id', '=', 'products.id')
                ->where('products.product_type', $type)
                ->select('items.*', 'products.product_type')
                ->get();

            return response()->json(['data' => $items]);
        });
    }

    /**
     * @test
     * @dataProvider productTypeProvider
     */
    public function it_gets_items_by_any_product_type(string $type)
    {
        // 1. Buat produk menggunakan enum valid
        $product = Product::factory()->create([
            'product_type' => $type
        ]);
        $this->assertDatabaseHas('products', [
            'id'           => $product->id,
            'product_type' => $type,
        ]);

        // 2. Tambahkan 2 item dummy
        $itemId1 = DB::table('items')->insertGetId([
            'product_id' => $product->id, 'name' => 'Item 1', 'qty' => 5,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $itemId2 = DB::table('items')->insertGetId([
            'product_id' => $product->id, 'name' => 'Item 2', 'qty' => 8,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // 3. Panggil endpoint dengan parameter dynamic
        $response = $this->getJson("api/items?type=$type");

        // 4. Verifikasi respon JSON sesuai
        $response->assertOk()
                 ->assertJsonCount(2, 'data')
                 ->assertJsonFragment(['id' => $itemId1])
                 ->assertJsonFragment(['id' => $itemId2]);

        foreach ($response->json('data') as $item) {
            $this->assertEquals($type, $item['product_type']);
        }
    }

    public static function productTypeProvider(): array
    {
        // Ambil semua nilai valid dari enum sebagai string
        return array_map(
            fn(ProductType $enum) => [$enum->value],
            ProductType::cases()
        );
    }
}
