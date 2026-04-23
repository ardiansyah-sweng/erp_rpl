<?php

namespace Tests\Unit\Model;

use App\Models\SupplierMaterial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class GetSupplierMaterialTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Migrate database before tests
        $this->artisan('migrate');
    }

    public function testGetSupplierMaterialReturnsPaginatedData()
    {
        // Insert dummy data directly into supplier_product table
        for ($i = 1; $i <= 15; $i++) {
            DB::table('supplier_product')->insert([
                'supplier_id' => 'SUP' . $i,
                'company_name' => 'Company ' . $i,
                'product_id' => 'PROD' . $i,
                'product_name' => 'Product ' . $i,
                'base_price' => 1000 + $i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $result = SupplierMaterial::getSupplierMaterial();

        // Assert the result is instance of LengthAwarePaginator
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);

        // Assert the number of items per page is 10
        $this->assertCount(10, $result->items());

        // Assert the first item matches the inserted data
        $firstItem = $result->items()[0];
        $this->assertEquals('SUP1', $firstItem->supplier_id);
        $this->assertEquals('Company 1', $firstItem->company_name);
        $this->assertEquals('PROD1', $firstItem->product_id);
        $this->assertEquals('Product 1', $firstItem->product_name);
        $this->assertEquals(1001, $firstItem->base_price);
    }

    public function testGetSupplierMaterialReturnsEmptyCollectionWhenNoData()
    {
        // Pastikan tabel supplier_product kosong
        DB::table('supplier_product')->truncate();

        $result = SupplierMaterial::getSupplierMaterial();

        // Assert result adalah instance LengthAwarePaginator
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);

        // Assert jumlah item 0
        $this->assertCount(0, $result->items());
    }

    public function testGetSupplierMaterialPaginationWithLargeDataset()
    {
        // Insert dummy lebih dari 50 data untuk testing paging
        for ($i = 1; $i <= 55; $i++) {
            DB::table('supplier_product')->insert([
                'supplier_id' => 'SUP' . $i,
                'company_name' => 'Company ' . $i,
                'product_id' => 'PROD' . $i,
                'product_name' => 'Product ' . $i,
                'base_price' => 1000 + $i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $result = SupplierMaterial::getSupplierMaterial();

        // Assert adalah LengthAwarePaginator
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);

        // Assert jumlah item per halaman adalah 10
        $this->assertCount(10, $result->items());

        // Assert total data adalah 55
        $this->assertEquals(55, $result->total());

        // Assert halaman aktif adalah 1
        $this->assertEquals(1, $result->currentPage());

        // Assert item pertama di halaman 1 benar
        $firstItem = $result->items()[0];
        $this->assertEquals('SUP1', $firstItem->supplier_id);
    }
}
