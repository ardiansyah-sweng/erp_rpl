<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WarehouseTest extends TestCase
{
    use RefreshDatabase;

    protected $warehouseModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->warehouseModel = new Warehouse();
    }

    /**
     * Test: Berhasil mengupdate gudang dengan data yang valid
     */
    public function test_berhasil_update_gudang_dengan_data_valid()
    {
        // Arrange (Persiapan)
        $gudang = Warehouse::factory()->create([
            'warehouse_name' => 'Gudang Lama',
            'warehouse_address' => 'Alamat Lama',
        ]);

        $dataUpdate = [
            'warehouse_name' => 'Gudang Baru',
            'warehouse_address' => 'Alamat Baru',
        ];

        // Act (Eksekusi)
        $hasil = $this->warehouseModel->updateWarehouse($gudang->id, $dataUpdate);

        // Assert (Verifikasi)
        $this->assertTrue($hasil);
        $this->assertDatabaseHas('warehouses', [
            'id' => $gudang->id,
            'warehouse_name' => 'Gudang Baru',
            'warehouse_address' => 'Alamat Baru',
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
            'name' => 'Nama Baru',
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
        $gudang = Warehouse::factory()->create([
            'warehouse_name' => 'Nama Asli',
            'warehouse_address' => 'Alamat Asli',
            'warehouse_phone' => '123456789',
            'is_active' => true,
        ]);

        $dataUpdate = [
            'warehouse_name' => 'Nama Terupdate',
            'warehouse_address' => 'Alamat Terupdate',
            'warehouse_phone' => '987654321',
            'is_active' => false,
        ];

        // Act
        $hasil = $this->warehouseModel->updateWarehouse($gudang->id, $dataUpdate);

        // Assert
        $this->assertTrue($hasil);
        $this->assertDatabaseHas('warehouses', [
            'id' => $gudang->id,
            'warehouse_name' => 'Nama Terupdate',
            'warehouse_address' => 'Alamat Terupdate',
            'warehouse_phone' => '987654321',
            'is_active' => false,
        ]);
    }

    /**
     * Test: Update gudang dengan data kosong
     */
    public function test_update_gudang_dengan_data_kosong()
    {
        // Arrange
        $gudang = Warehouse::factory()->create([
            'warehouse_name' => 'Nama Asli',
        ]);

        $dataUpdate = [];

        // Act
        $hasil = $this->warehouseModel->updateWarehouse($gudang->id, $dataUpdate);

        // Assert
        $this->assertTrue($hasil);
        $this->assertDatabaseHas('warehouses', [
            'id' => $gudang->id,
            'warehouse_name' => 'Nama Asli',
        ]);
    }

    /**
     * Test: Update gudang mempertahankan field yang tidak diubah
     */
    public function test_update_gudang_mempertahankan_field_tidak_diubah()
    {
        // Arrange
        $dataAsli = [
            'warehouse_name' => 'Nama Asli',
            'warehouse_address' => 'Alamat Asli',
            'warehouse_phone' => '123456789',
            'is_active' => true,
        ];

        $gudang = Warehouse::factory()->create($dataAsli);

        $dataUpdate = [
            'warehouse_name' => 'Nama Terupdate',
        ];

        // Act
        $this->warehouseModel->updateWarehouse($gudang->id, $dataUpdate);

        // Assert
        $this->assertDatabaseHas('warehouses', [
            'id' => $gudang->id,
            'warehouse_name' => 'Nama Terupdate',
            'warehouse_address' => 'Alamat Asli',
            'warehouse_phone' => '123456789',
            'is_active' => true,
        ]);
    }

    /**
     * Test: Update gudang dengan karakter khusus
     */
    public function test_update_gudang_dengan_karakter_khusus()
    {
        // Arrange
        $gudang = Warehouse::factory()->create();

        $dataUpdate = [
            'warehouse_name' => 'Gudang Pabrik @#$%',
            'warehouse_address' => 'Jl. Sudirman No. 123, Jakarta Selatan',
            'warehouse_phone' => '+62-812-3456-7890',
        ];

        // Act
        $hasil = $this->warehouseModel->updateWarehouse($gudang->id, $dataUpdate);

        // Assert
        $this->assertTrue($hasil);
        $this->assertDatabaseHas('warehouses', [
            'id' => $gudang->id,
            'warehouse_name' => 'Gudang Pabrik @#$%',
            'warehouse_address' => 'Jl. Sudirman No. 123, Jakarta Selatan',
            'warehouse_phone' => '+62-812-3456-7890',
        ]);
    }

    /**
     * Test: Update flag tipe gudang
     */
    public function test_update_flag_tipe_gudang()
    {
        // Arrange
        $gudang = Warehouse::factory()->create([
            'is_rm_warehouse' => false,
            'is_fg_warehouse' => false,
        ]);

        $dataUpdate = [
            'is_rm_warehouse' => true,
            'is_fg_warehouse' => true,
        ];

        // Act
        $hasil = $this->warehouseModel->updateWarehouse($gudang->id, $dataUpdate);

        // Assert
        $this->assertTrue($hasil);
        $this->assertDatabaseHas('warehouses', [
            'id' => $gudang->id,
            'is_rm_warehouse' => true,
            'is_fg_warehouse' => true,
        ]);
    }

    /**
     * Test: Update gudang dengan ID berupa string
     */
    public function test_update_gudang_dengan_id_string()
    {
        // Arrange
        $gudang = Warehouse::factory()->create([
            'warehouse_name' => 'Nama Asli',
        ]);

        $dataUpdate = [
            'warehouse_name' => 'Nama Terupdate',
        ];

        // Act
        $hasil = $this->warehouseModel->updateWarehouse((string)$gudang->id, $dataUpdate);

        // Assert
        $this->assertTrue($hasil);
        $this->assertDatabaseHas('warehouses', [
            'id' => $gudang->id,
            'warehouse_name' => 'Nama Terupdate',
        ]);
    }

    /**
     * Test: Update gudang dengan nilai null
     */
    public function test_update_gudang_dengan_nilai_null()
    {
        // Arrange
        $gudang = Warehouse::factory()->create([
            'warehouse_name' => 'Nama Asli',
            'warehouse_phone' => '123456789',
        ]);

        $dataUpdate = [
            'warehouse_phone' => null,
        ];

        // Act
        $hasil = $this->warehouseModel->updateWarehouse($gudang->id, $dataUpdate);

        // Assert
        $this->assertTrue($hasil);
        $this->assertDatabaseHas('warehouses', [
            'id' => $gudang->id,
            'warehouse_phone' => null,
        ]);
    }

    /**
     * Test: Update gudang berkali-kali secara berurutan
     */
    public function test_update_gudang_berkali_kali_berurutan()
    {
        // Arrange
        $gudang = Warehouse::factory()->create([
            'warehouse_name' => 'Nama Asli',
        ]);

        // Act - Update pertama
        $hasil1 = $this->warehouseModel->updateWarehouse($gudang->id, [
            'warehouse_name' => 'Update Pertama',
        ]);

        // Act - Update kedua
        $hasil2 = $this->warehouseModel->updateWarehouse($gudang->id, [
            'warehouse_name' => 'Update Kedua',
        ]);

        // Assert
        $this->assertTrue($hasil1);
        $this->assertTrue($hasil2);
        $this->assertDatabaseHas('warehouses', [
            'id' => $gudang->id,
            'warehouse_name' => 'Update Kedua',
        ]);
    }

    /**
     * Test: Update gudang dengan ID nol
     */
    public function test_update_gudang_dengan_id_nol()
    {
        // Arrange
        $dataUpdate = [
            'name' => 'Nama Terupdate',
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
            'name' => 'Nama Terupdate',
        ];

        // Act
        $hasil = $this->warehouseModel->updateWarehouse(-1, $dataUpdate);

        // Assert
        $this->assertFalse($hasil);
    }
}