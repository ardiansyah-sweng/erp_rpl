<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Warehouse;
use App\Constants\WarehouseColumns;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class WarehouseTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $warehouseModel;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup database tables
        $this->artisan('migrate');
        $this->warehouseModel = new Warehouse();
    }

    /**
     * Test getWarehouseAll() without search parameter
     */
    public function test_get_warehouse_all_returns_all_warehouses()
    {
        // Arrange - Create test warehouses
        $warehouse1 = Warehouse::create([
            WarehouseColumns::NAME => 'Warehouse Alpha',
            WarehouseColumns::ADDRESS => 'Jl. Alpha No. 1',
            WarehouseColumns::PHONE => '021-1111111',
            WarehouseColumns::IS_RM_WAREHOUSE => true,
            WarehouseColumns::IS_FG_WAREHOUSE => false,
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        $warehouse2 = Warehouse::create([
            WarehouseColumns::NAME => 'Warehouse Beta',
            WarehouseColumns::ADDRESS => 'Jl. Beta No. 2',
            WarehouseColumns::PHONE => '021-2222222',
            WarehouseColumns::IS_RM_WAREHOUSE => false,
            WarehouseColumns::IS_FG_WAREHOUSE => true,
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        // Act - Call getWarehouseAll without search
        $result = Warehouse::getWarehouseAll();

        // Assert - Should return paginated results with all warehouses
        $this->assertNotNull($result);
        $this->assertEquals(2, $result->total());
        $this->assertCount(2, $result->items());
        
        // Assert data contains created warehouses
        $warehouseNames = $result->pluck(WarehouseColumns::NAME)->toArray();
        $this->assertContains('Warehouse Alpha', $warehouseNames);
        $this->assertContains('Warehouse Beta', $warehouseNames);
    }

    /**
     * Test getWarehouseAll() with search parameter - search by name
     */
    public function test_get_warehouse_all_with_search_by_name()
    {
        // Arrange - Create test warehouses
        Warehouse::create([
            WarehouseColumns::NAME => 'Central Warehouse Jakarta',
            WarehouseColumns::ADDRESS => 'Jl. Sudirman No. 1',
            WarehouseColumns::PHONE => '021-1111111',
            WarehouseColumns::IS_RM_WAREHOUSE => true,
            WarehouseColumns::IS_FG_WAREHOUSE => false,
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        Warehouse::create([
            WarehouseColumns::NAME => 'Branch Warehouse Bandung',
            WarehouseColumns::ADDRESS => 'Jl. Asia Afrika No. 2',
            WarehouseColumns::PHONE => '022-2222222',
            WarehouseColumns::IS_RM_WAREHOUSE => false,
            WarehouseColumns::IS_FG_WAREHOUSE => true,
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        // Act - Search by name containing 'Jakarta'
        $result = Warehouse::getWarehouseAll('Jakarta');

        // Assert - Should return only warehouses with 'Jakarta' in name
        $this->assertNotNull($result);
        $this->assertEquals(1, $result->total());
        $this->assertCount(1, $result->items());
        $this->assertStringContainsString('Jakarta', $result->first()->warehouse_name);
    }

    /**
     * Test getWarehouseAll() with search parameter - search by address
     */
    public function test_get_warehouse_all_with_search_by_address()
    {
        // Arrange - Create test warehouses
        Warehouse::create([
            WarehouseColumns::NAME => 'Warehouse A',
            WarehouseColumns::ADDRESS => 'Jl. Sudirman Jakarta Pusat',
            WarehouseColumns::PHONE => '021-1111111',
            WarehouseColumns::IS_RM_WAREHOUSE => true,
            WarehouseColumns::IS_FG_WAREHOUSE => false,
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        Warehouse::create([
            WarehouseColumns::NAME => 'Warehouse B',
            WarehouseColumns::ADDRESS => 'Jl. Asia Afrika Bandung',
            WarehouseColumns::PHONE => '022-2222222',
            WarehouseColumns::IS_RM_WAREHOUSE => false,
            WarehouseColumns::IS_FG_WAREHOUSE => true,
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        // Act - Search by address containing 'Sudirman'
        $result = Warehouse::getWarehouseAll('Sudirman');

        // Assert - Should return only warehouses with 'Sudirman' in address
        $this->assertNotNull($result);
        $this->assertEquals(1, $result->total());
        $this->assertCount(1, $result->items());
        $this->assertStringContainsString('Sudirman', $result->first()->warehouse_address);
    }

    /**
     * Test getWarehouseAll() with search parameter - search by phone
     */
    public function test_get_warehouse_all_with_search_by_phone()
    {
        // Arrange - Create test warehouses
        Warehouse::create([
            WarehouseColumns::NAME => 'Warehouse A',
            WarehouseColumns::ADDRESS => 'Jl. Test A',
            WarehouseColumns::PHONE => '021-1234567',
            WarehouseColumns::IS_RM_WAREHOUSE => true,
            WarehouseColumns::IS_FG_WAREHOUSE => false,
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        Warehouse::create([
            WarehouseColumns::NAME => 'Warehouse B',
            WarehouseColumns::ADDRESS => 'Jl. Test B',
            WarehouseColumns::PHONE => '022-7654321',
            WarehouseColumns::IS_RM_WAREHOUSE => false,
            WarehouseColumns::IS_FG_WAREHOUSE => true,
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        // Act - Search by phone containing '021'
        $result = Warehouse::getWarehouseAll('021');

        // Assert - Should return only warehouses with '021' in phone
        $this->assertNotNull($result);
        $this->assertEquals(1, $result->total());
        $this->assertCount(1, $result->items());
        $this->assertStringContainsString('021', $result->first()->warehouse_phone);
    }

    /**
     * Test getWarehouseAll() with search that returns no results
     */
    public function test_get_warehouse_all_with_search_no_results()
    {
        // Arrange - Create test warehouse
        Warehouse::create([
            WarehouseColumns::NAME => 'Test Warehouse',
            WarehouseColumns::ADDRESS => 'Jl. Test',
            WarehouseColumns::PHONE => '021-1111111',
            WarehouseColumns::IS_RM_WAREHOUSE => true,
            WarehouseColumns::IS_FG_WAREHOUSE => false,
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        // Act - Search with keyword that doesn't exist
        $result = Warehouse::getWarehouseAll('NonExistentKeyword');

        // Assert - Should return empty paginated result
        $this->assertNotNull($result);
        $this->assertEquals(0, $result->total());
        $this->assertCount(0, $result->items());
    }

    /**
     * Test getWarehouseAll() returns paginated results
     */
    public function test_get_warehouse_all_returns_paginated_results()
    {
        // Arrange - Create multiple warehouses
        for ($i = 1; $i <= 20; $i++) {
            Warehouse::create([
                WarehouseColumns::NAME => "Warehouse {$i}",
                WarehouseColumns::ADDRESS => "Jl. Test {$i}",
                WarehouseColumns::PHONE => "021-{$i}111111",
                WarehouseColumns::IS_RM_WAREHOUSE => $i % 2 === 0,
                WarehouseColumns::IS_FG_WAREHOUSE => $i % 2 === 1,
                WarehouseColumns::IS_ACTIVE => true,
            ]);
        }

        // Act - Get paginated results
        $result = Warehouse::getWarehouseAll();

        // Assert - Should return paginated results
        $this->assertNotNull($result);
        $this->assertEquals(20, $result->total());
        
        // Assert pagination metadata exists
        $this->assertNotNull($result->currentPage());
        $this->assertNotNull($result->perPage());
        $this->assertNotNull($result->lastPage());
    }

    /**
     * Test getWarehouseAll() with empty database
     */
    public function test_get_warehouse_all_with_empty_database()
    {
        // Act - Call getWarehouseAll with empty database
        $result = Warehouse::getWarehouseAll();

        // Assert - Should return empty paginated result
        $this->assertNotNull($result);
        $this->assertEquals(0, $result->total());
        $this->assertCount(0, $result->items());
    }

    /**
     * Test getWarehouseAll() ordering - should be ordered by created_at asc
     */
    public function test_get_warehouse_all_ordering()
    {
        // Arrange - Create warehouses with different timestamps
        $warehouse1 = Warehouse::create([
            WarehouseColumns::NAME => 'First Warehouse',
            WarehouseColumns::ADDRESS => 'Jl. First',
            WarehouseColumns::PHONE => '021-1111111',
            WarehouseColumns::IS_RM_WAREHOUSE => true,
            WarehouseColumns::IS_FG_WAREHOUSE => false,
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        // Small delay to ensure different timestamps
        sleep(1);

        $warehouse2 = Warehouse::create([
            WarehouseColumns::NAME => 'Second Warehouse',
            WarehouseColumns::ADDRESS => 'Jl. Second',
            WarehouseColumns::PHONE => '021-2222222',
            WarehouseColumns::IS_RM_WAREHOUSE => false,
            WarehouseColumns::IS_FG_WAREHOUSE => true,
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        // Act - Get all warehouses
        $result = Warehouse::getWarehouseAll();

        // Assert - Should be ordered by created_at ascending (oldest first)
        $this->assertNotNull($result);
        $this->assertEquals(2, $result->total());
        $this->assertEquals('First Warehouse', $result->first()->warehouse_name);
        $this->assertEquals('Second Warehouse', $result->last()->warehouse_name);
    }

    /**
     * Test: Berhasil mengupdate gudang dengan data yang valid
     */
    public function test_berhasil_update_gudang_dengan_data_valid()
    {
        // Arrange (Persiapan)
        $gudang = Warehouse::create([
            WarehouseColumns::NAME => 'Gudang Lama',
            WarehouseColumns::ADDRESS => 'Alamat Lama',
            WarehouseColumns::PHONE => '021-1111111',
            WarehouseColumns::IS_RM_WAREHOUSE => true,
            WarehouseColumns::IS_FG_WAREHOUSE => false,
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        $dataUpdate = [
            WarehouseColumns::NAME => 'Gudang Baru',
            WarehouseColumns::ADDRESS => 'Alamat Baru',
        ];

        // Act (Eksekusi)
        $hasil = $this->warehouseModel->updateWarehouse($gudang->id, $dataUpdate);

        // Assert (Verifikasi)
        $this->assertTrue($hasil);
        $this->assertDatabaseHas(config('db_tables.warehouse'), [
            'id' => $gudang->id,
            WarehouseColumns::NAME => 'Gudang Baru',
            WarehouseColumns::ADDRESS => 'Alamat Baru',
        ]);
    }

    /**
     * Test: Update gudang mengembalikan false ketika gudang tidak ditemukan
     */
    public function test_update_gudang_return_false_ketika_tidak_ditemukan()
    {
        // Arrange
        $idTidakAda = 99999;
        $dataUpdate = [
            WarehouseColumns::NAME => 'Nama Baru',
        ];

        // Act
        $hasil = $this->warehouseModel->updateWarehouse($idTidakAda, $dataUpdate);

        // Assert
        $this->assertFalse($hasil);
    }

    /**
     * Test: Update gudang dengan banyak field
     */
    public function test_update_gudang_dengan_banyak_field()
    {
        // Arrange
        $gudang = Warehouse::create([
            WarehouseColumns::NAME => 'Nama Asli',
            WarehouseColumns::ADDRESS => 'Alamat Asli',
            WarehouseColumns::PHONE => '123456789',
            WarehouseColumns::IS_RM_WAREHOUSE => true,
            WarehouseColumns::IS_FG_WAREHOUSE => false,
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        $dataUpdate = [
            WarehouseColumns::NAME => 'Nama Terupdate',
            WarehouseColumns::ADDRESS => 'Alamat Terupdate',
            WarehouseColumns::PHONE => '987654321',
            WarehouseColumns::IS_ACTIVE => false,
        ];

        // Act
        $hasil = $this->warehouseModel->updateWarehouse($gudang->id, $dataUpdate);

        // Assert
        $this->assertTrue($hasil);
        $this->assertDatabaseHas(config('db_tables.warehouse'), [
            'id' => $gudang->id,
            WarehouseColumns::NAME => 'Nama Terupdate',
            WarehouseColumns::ADDRESS => 'Alamat Terupdate',
            WarehouseColumns::PHONE => '987654321',
            WarehouseColumns::IS_ACTIVE => false,
        ]);
    }

    /**
     * Test: Update gudang dengan data kosong
     */
    public function test_update_gudang_dengan_data_kosong()
    {
        // Arrange
        $gudang = Warehouse::create([
            WarehouseColumns::NAME => 'Nama Asli',
            WarehouseColumns::ADDRESS => 'Alamat Asli',
            WarehouseColumns::PHONE => '021-1111111',
            WarehouseColumns::IS_RM_WAREHOUSE => true,
            WarehouseColumns::IS_FG_WAREHOUSE => false,
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        $dataUpdate = [];

        // Act
        $hasil = $this->warehouseModel->updateWarehouse($gudang->id, $dataUpdate);

        // Assert
        $this->assertTrue($hasil);
        $this->assertDatabaseHas(config('db_tables.warehouse'), [
            'id' => $gudang->id,
            WarehouseColumns::NAME => 'Nama Asli',
        ]);
    }

    /**
     * Test: Update gudang mempertahankan field yang tidak diubah
     */
    public function test_update_gudang_mempertahankan_field_tidak_diubah()
    {
        // Arrange
        $dataAsli = [
            WarehouseColumns::NAME => 'Nama Asli',
            WarehouseColumns::ADDRESS => 'Alamat Asli',
            WarehouseColumns::PHONE => '123456789',
            WarehouseColumns::IS_RM_WAREHOUSE => true,
            WarehouseColumns::IS_FG_WAREHOUSE => false,
            WarehouseColumns::IS_ACTIVE => true,
        ];

        $gudang = Warehouse::create($dataAsli);

        $dataUpdate = [
            WarehouseColumns::NAME => 'Nama Terupdate',
        ];

        // Act
        $this->warehouseModel->updateWarehouse($gudang->id, $dataUpdate);

        // Assert
        $this->assertDatabaseHas(config('db_tables.warehouse'), [
            'id' => $gudang->id,
            WarehouseColumns::NAME => 'Nama Terupdate',
            WarehouseColumns::ADDRESS => 'Alamat Asli',
            WarehouseColumns::PHONE => '123456789',
            WarehouseColumns::IS_ACTIVE => true,
        ]);
    }

    /**
     * Test: Update gudang dengan karakter khusus
     */
    public function test_update_gudang_dengan_karakter_khusus()
    {
        // Arrange
        $gudang = Warehouse::create([
            WarehouseColumns::NAME => 'Gudang Lama',
            WarehouseColumns::ADDRESS => 'Alamat Lama',
            WarehouseColumns::PHONE => '021-1111111',
            WarehouseColumns::IS_RM_WAREHOUSE => true,
            WarehouseColumns::IS_FG_WAREHOUSE => false,
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        $dataUpdate = [
            WarehouseColumns::NAME => 'Gudang Pabrik @#$%',
            WarehouseColumns::ADDRESS => 'Jl. Sudirman No. 123, Jakarta Selatan',
            WarehouseColumns::PHONE => '+62-812-3456-7890',
        ];

        // Act
        $hasil = $this->warehouseModel->updateWarehouse($gudang->id, $dataUpdate);

        // Assert
        $this->assertTrue($hasil);
        $this->assertDatabaseHas(config('db_tables.warehouse'), [
            'id' => $gudang->id,
            WarehouseColumns::NAME => 'Gudang Pabrik @#$%',
            WarehouseColumns::ADDRESS => 'Jl. Sudirman No. 123, Jakarta Selatan',
            WarehouseColumns::PHONE => '+62-812-3456-7890',
        ]);
    }

    /**
     * Test: Update flag tipe gudang
     */
    public function test_update_gudang_flag_tipe_gudang()
    {
        // Arrange
        $gudang = Warehouse::create([
            WarehouseColumns::NAME => 'Gudang Test',
            WarehouseColumns::ADDRESS => 'Alamat Test',
            WarehouseColumns::PHONE => '021-1111111',
            WarehouseColumns::IS_RM_WAREHOUSE => false,
            WarehouseColumns::IS_FG_WAREHOUSE => false,
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        $dataUpdate = [
            WarehouseColumns::IS_RM_WAREHOUSE => true,
            WarehouseColumns::IS_FG_WAREHOUSE => true,
        ];

        // Act
        $hasil = $this->warehouseModel->updateWarehouse($gudang->id, $dataUpdate);

        // Assert
        $this->assertTrue($hasil);
        $this->assertDatabaseHas(config('db_tables.warehouse'), [
            'id' => $gudang->id,
            WarehouseColumns::IS_RM_WAREHOUSE => true,
            WarehouseColumns::IS_FG_WAREHOUSE => true,
        ]);
    }

    /**
     * Test: Update gudang dengan ID berupa string
     */
    public function test_update_gudang_dengan_id_string()
    {
        // Arrange
        $gudang = Warehouse::create([
            WarehouseColumns::NAME => 'Nama Asli',
            WarehouseColumns::ADDRESS => 'Alamat Asli',
            WarehouseColumns::PHONE => '021-1111111',
            WarehouseColumns::IS_RM_WAREHOUSE => true,
            WarehouseColumns::IS_FG_WAREHOUSE => false,
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        $dataUpdate = [
            WarehouseColumns::NAME => 'Nama Terupdate',
        ];

        // Act
        $hasil = $this->warehouseModel->updateWarehouse((string)$gudang->id, $dataUpdate);

        // Assert
        $this->assertTrue($hasil);
        $this->assertDatabaseHas(config('db_tables.warehouse'), [
            'id' => $gudang->id,
            WarehouseColumns::NAME => 'Nama Terupdate',
        ]);
    }

    /**
     * Test: Update gudang dengan nilai null
     */
    public function test_update_gudang_dengan_nilai_null()
    {
        // Arrange
        $gudang = Warehouse::create([
            WarehouseColumns::NAME => 'Nama Asli',
            WarehouseColumns::ADDRESS => 'Alamat Asli',
            WarehouseColumns::PHONE => '123456789',
            WarehouseColumns::IS_RM_WAREHOUSE => true,
            WarehouseColumns::IS_FG_WAREHOUSE => false,
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        $dataUpdate = [
            WarehouseColumns::PHONE => null,
        ];

        // Act
        $hasil = $this->warehouseModel->updateWarehouse($gudang->id, $dataUpdate);

        // Assert
        $this->assertTrue($hasil);
        $this->assertDatabaseHas(config('db_tables.warehouse'), [
            'id' => $gudang->id,
            WarehouseColumns::PHONE => null,
        ]);
    }

    /**
     * Test: Update gudang berkali-kali secara berurutan
     */
    public function test_update_gudang_berkali_kali_berurutan()
    {
        // Arrange
        $gudang = Warehouse::create([
            WarehouseColumns::NAME => 'Nama Asli',
            WarehouseColumns::ADDRESS => 'Alamat Asli',
            WarehouseColumns::PHONE => '021-1111111',
            WarehouseColumns::IS_RM_WAREHOUSE => true,
            WarehouseColumns::IS_FG_WAREHOUSE => false,
            WarehouseColumns::IS_ACTIVE => true,
        ]);

        // Act - Update pertama
        $hasil1 = $this->warehouseModel->updateWarehouse($gudang->id, [
            WarehouseColumns::NAME => 'Update Pertama',
        ]);

        // Act - Update kedua
        $hasil2 = $this->warehouseModel->updateWarehouse($gudang->id, [
            WarehouseColumns::NAME => 'Update Kedua',
        ]);

        // Assert
        $this->assertTrue($hasil1);
        $this->assertTrue($hasil2);
        $this->assertDatabaseHas(config('db_tables.warehouse'), [
            'id' => $gudang->id,
            WarehouseColumns::NAME => 'Update Kedua',
        ]);
    }

    /**
     * Test: Update gudang dengan ID nol
     */
    public function test_update_gudang_dengan_id_nol()
    {
        // Arrange
        $dataUpdate = [
            WarehouseColumns::NAME => 'Nama Terupdate',
        ];

        // Act
        $hasil = $this->warehouseModel->updateWarehouse(0, $dataUpdate);

        // Assert
        $this->assertFalse($hasil);
    }

    /**
     * Test: Update gudang dengan ID negatif
     */
    public function test_update_gudang_dengan_id_negatif()
    {
        // Arrange
        $dataUpdate = [
            WarehouseColumns::NAME => 'Nama Terupdate',
        ];

        // Act
        $hasil = $this->warehouseModel->updateWarehouse(-1, $dataUpdate);

        // Assert
        $this->assertFalse($hasil);
    }
}