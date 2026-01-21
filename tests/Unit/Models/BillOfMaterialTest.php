<?php

namespace Tests\Unit\Models;

use App\Models\BillOfMaterial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BillOfMaterialTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup: Jalankan migration dan seeder untuk setup data test
     * RefreshDatabase akan otomatis menjalankan semua migration dan seeder
     */
    protected function setUp(): void
    {
        parent::setUp();
        // Data measurement_unit sudah ada dari migration/seeder
    }

    /**
     * Test Case 1: Update BOM yang ada dengan data valid
     * 
     * Scenario: Ketika kita update BOM yang sudah ada di database
     * dengan data yang valid, maka:
     * - Method harus return object BOM yang sudah diupdate
     * - Data di database harus berubah sesuai input
     * - Field yang tidak di-update tetap sama
     */
    public function test_update_existing_bom_with_valid_data()
    {
        // Arrange: Buat data BOM awal
        // measurement_unit menggunakan ID dari data yang sudah ada di database
        $bom = BillOfMaterial::create([
            'bom_id'           => 'BOM-001',
            'bom_name'         => 'BOM Original',
            'measurement_unit' => 1, // ID dari measurement_unit yang sudah ada
            'total_cost'       => 50000,
            'active'           => true,
        ]);

        $updateData = [
            'bom_name'   => 'BOM Updated',
            'total_cost' => 75000,
        ];

        // Act: Update BOM
        $result = BillOfMaterial::updateBillOfMaterial($bom->id, $updateData);

        // Assert
        $this->assertNotNull($result);
        $this->assertIsObject($result);
        $this->assertEquals('BOM Updated', $result->bom_name);
        $this->assertEquals(75000, $result->total_cost);
        // Field yang tidak diupdate harus tetap sama
        $this->assertEquals('BOM-001', $result->bom_id);
        $this->assertEquals(1, $result->measurement_unit);
        $this->assertEquals(true, (bool)$result->active);

        // Verify data benar-benar tersimpan di database
        $this->assertDatabaseHas('bill_of_material', [
            'id'         => $bom->id,
            'bom_name'   => 'BOM Updated',
            'total_cost' => 75000,
        ]);
    }

    /**
     * Test Case 2: Update BOM yang tidak ada (tidak ditemukan)
     * 
     * Scenario: Ketika kita mencoba update BOM dengan ID yang tidak ada
     * di database, maka:
     * - Method harus return null
     * - Tidak ada data yang berubah di database
     */
    public function test_update_non_existing_bom_returns_null()
    {
        // Arrange: ID yang tidak ada di database
        $nonExistingId = 999;
        $updateData = [
            'bom_name' => 'BOM Updated',
        ];

        // Act: Coba update BOM yang tidak ada
        $result = BillOfMaterial::updateBillOfMaterial($nonExistingId, $updateData);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test Case 3: Update hanya field tertentu (partial update)
     * 
     * Scenario: Ketika kita update hanya beberapa field dari BOM,
     * maka:
     * - Hanya field yang di-update yang berubah
     * - Field lainnya tetap dengan nilai awal
     * - Method tetap return object BOM yang diupdate
     */
    public function test_update_bom_partial_data()
    {
        // Arrange: Buat BOM dengan berbagai field
        // measurement_unit menggunakan ID dari data yang sudah ada di database
        $bom = BillOfMaterial::create([
            'bom_id'           => 'BOM-002',
            'bom_name'         => 'Resep Asli',
            'measurement_unit' => 2, // ID measurement_unit yang sudah ada
            'total_cost'       => 100000,
            'active'           => true,
        ]);

        // Update hanya status active
        $updateData = [
            'active' => false,
        ];

        // Act: Update partial data
        $result = BillOfMaterial::updateBillOfMaterial($bom->id, $updateData);

        // Assert
        $this->assertNotNull($result);
        $this->assertFalse($result->active);
        // Field lain tetap sama
        $this->assertEquals('Resep Asli', $result->bom_name);
        $this->assertEquals('BOM-002', $result->bom_id);
        $this->assertEquals(100000, $result->total_cost);
        $this->assertEquals(2, $result->measurement_unit);

        // Verify di database
        $this->assertDatabaseHas('bill_of_material', [
            'id'     => $bom->id,
            'active' => false,
            'bom_name' => 'Resep Asli',
        ]);
    }

    /**
     * Test Case 4: Update BOM dengan multiple field sekaligus
     * 
     * Scenario: Ketika kita update BOM dengan multiple field
     * secara bersamaan, maka:
     * - Semua field yang di-update harus berubah dengan benar
     * - Method return object dengan data yang benar
     * - Timestamp updated_at harus terupdate
     */
    public function test_update_bom_with_multiple_fields()
    {
        // Arrange: Buat BOM awal
        // measurement_unit adalah ID (tinyInteger)
        $bomBefore = BillOfMaterial::create([
            'bom_id'           => 'BOM-003',
            'bom_name'         => 'Resep Lama',
            'measurement_unit' => 31,
            'total_cost'       => 25000,
            'active'           => true,
        ]);

        $oldUpdatedAt = $bomBefore->updated_at;

        // Update multiple field
        $updateData = [
            'bom_name'   => 'Resep Baru',
            'total_cost' => 50000,
            'active'     => false,
        ];

        // Act: Update multiple fields
        $result = BillOfMaterial::updateBillOfMaterial($bomBefore->id, $updateData);

        // Assert: Cek semua field yang diupdate
        $this->assertNotNull($result);
        $this->assertEquals('Resep Baru', $result->bom_name);
        $this->assertEquals(50000, $result->total_cost);
        $this->assertFalse($result->active);
        // bom_id dan measurement_unit tidak boleh berubah
        $this->assertEquals('BOM-003', $result->bom_id);
        $this->assertEquals(31, $result->measurement_unit);

        // Cek timestamp updated_at berubah
        $this->assertGreaterThanOrEqual($oldUpdatedAt, $result->updated_at);

        // Verify semua perubahan di database
        $this->assertDatabaseHas('bill_of_material', [
            'id'               => $bomBefore->id,
            'bom_id'           => 'BOM-003',
            'bom_name'         => 'Resep Baru',
            'total_cost'       => 50000,
            'active'           => false,
        ]);
    }
}