<?php

namespace Tests\Feature\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Product;
use App\Enums\ProductType;
use App\Models\MeasurementUnit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ItemControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Kita perlu membuat kategori terlebih dahulu karena produk membutuhkannya.
        Category::factory()->create();

        // FIX: Buat beberapa MeasurementUnit agar ItemFactory tidak error.
        // Error terjadi karena ItemFactory mencoba mengambil MeasurementUnit dari DB yang kosong.
        MeasurementUnit::factory()->count(5)->create();
    }

    #[Test]
    public function it_can_get_items_by_a_valid_product_type(): void
    {
        // Arrange: Siapkan data
        // 1. Buat produk dengan tipe 'Raw Material' (RM)
        $productRM = Product::factory()->create([
            'product_type' => ProductType::RM,
        ]);

        // 2. Buat produk dengan tipe 'Finished Good' (FG)
        $productFG = Product::factory()->create([
            'product_type' => ProductType::FG,
        ]);

        // 3. Buat 3 item yang terhubung dengan produk RM
        Item::factory()->count(3)->create([
            'product_id' => $productRM->id,
        ]);

        // 4. Buat 2 item yang terhubung dengan produk FG
        Item::factory()->count(2)->create([
            'product_id' => $productFG->id,
        ]);

        // Act: Lakukan request ke endpoint API
        // Asumsi route-nya adalah 'api/items/type/{productType}'
        $response = $this->getJson(route('api.items.by_type', ['productType' => 'RM']));

        // Assert: Verifikasi hasil
        $response->assertStatus(200); // Pastikan respons OK
        $response->assertJsonCount(3); // Pastikan hanya 3 item (RM) yang dikembalikan

        // Verifikasi bahwa setiap item yang dikembalikan memiliki product_type 'RM'
        $response->assertJsonFragment(['product_type' => 'RM']);
        $response->assertJsonMissing(['product_type' => 'FG']);
    }

    #[Test]
    public function it_returns_an_empty_array_for_a_product_type_with_no_items(): void
    {
        // Arrange: Buat beberapa item, tapi tidak ada untuk tipe 'HFG'
        $productRM = Product::factory()->create([
            'product_type' => ProductType::RM,
        ]);
        Item::factory()->count(5)->create([
            'product_id' => $productRM->id,
        ]);

        // Act: Lakukan request untuk tipe produk 'HFG' (Half-Finished Goods)
        $response = $this->getJson(route('api.items.by_type', ['productType' => 'HFG']));

        // Assert: Verifikasi hasilnya
        $response->assertStatus(200); // Respons tetap OK
        $response->assertJsonCount(0); // Pastikan array JSON yang dikembalikan kosong
        $response->assertContent('[]'); // Pastikan konten respons adalah array kosong
    }

    #[Test]
    public function it_returns_an_empty_array_for_a_non_existent_product_type(): void
    {
        // Arrange: Buat beberapa item, tapi dengan tipe produk yang berbeda.
        $productRM = Product::factory()->create([
            'product_type' => ProductType::RM,
        ]);
        Item::factory()->count(2)->create([
            'product_id' => $productRM->id,
        ]);

        // Act: Lakukan request dengan tipe produk yang tidak valid
        $response = $this->getJson(route('api.items.by_type', ['productType' => 'INVALID_TYPE']));

        // Assert: Verifikasi hasilnya
        $response->assertStatus(200); // Respons tetap OK
        $response->assertJsonCount(0); // Pastikan array JSON yang dikembalikan kosong
        $response->assertContent('[]'); // Pastikan konten respons adalah array kosong
    }
}