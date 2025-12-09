<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Supplier;
use App\Models\SupplierPic;

class SupplierPicModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_addSupplierPIC_creates_record_and_sets_supplier_id()
    {
        // Arrange
        $supplier = Supplier::factory()->create();

        $data = [
            'name' => 'John Doe',
            'phone_number' => '08123456789',
            'email' => 'john@example.com',
            'assigned_date' => now()->toDateString(),
            'active' => 1,
        ];

        // Act
        $pic = SupplierPic::addSupplierPIC($supplier->supplier_id, $data);

        // Assert: returned model is instance and has supplier_id
        $this->assertNotNull($pic);
        $this->assertEquals($supplier->supplier_id, $pic->supplier_id);

        // Assert: database has record in supplier_pic table
        $table = config('db_tables.supplier_pic');
        $this->assertDatabaseHas($table, [
            'supplier_id' => $supplier->supplier_id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
    }

    public function test_addSupplierPIC_with_minimal_data_still_creates_and_returns_model()
    {
        $supplier = Supplier::factory()->create();

        // minimal data (only name)
        $data = [
            'name' => 'Minimal Name'
        ];

        $pic = SupplierPic::addSupplierPIC($supplier->supplier_id, $data);

        $this->assertNotNull($pic);
        $this->assertEquals($supplier->supplier_id, $pic->supplier_id);

        $table = config('db_tables.supplier_pic');
        $this->assertDatabaseHas($table, [
            'supplier_id' => $supplier->supplier_id,
            'name' => 'Minimal Name',
        ]);
    }

    /**
     * Test Case 1: Update SupplierPIC with valid data returns success
     */
    public function test_updateSupplierPIC_with_valid_data_returns_success()
    {
        // Arrange: Create supplier and PIC
        $supplier = Supplier::factory()->create();
        $pic = SupplierPic::addSupplierPIC($supplier->supplier_id, [
            'name' => 'Original Name',
            'phone_number' => '08123456789',
            'email' => 'original@example.com',
            'active' => 1,
        ]);

        $updateData = [
            'name' => 'Updated Name',
            'phone_number' => '08987654321',
            'email' => 'updated@example.com',
            'active' => 0,
        ];

        // Act
        $result = SupplierPic::updateSupplierPIC($pic->id, $updateData);

        // Assert
        $this->assertEquals('success', $result['status']);
        $this->assertEquals(200, $result['code']);
        $this->assertNotNull($result['data']);

        $table = config('db_tables.supplier_pic');
        $this->assertDatabaseHas($table, [
            'id' => $pic->id,
            'name' => 'Updated Name',
            'phone_number' => '08987654321',
            'email' => 'updated@example.com',
            'active' => 0,
        ]);
    }

    /**
     * Test Case 2: Update non-existent PIC returns error
     */
    public function test_updateSupplierPIC_with_non_existent_id_returns_error()
    {
        // Arrange
        $nonExistentId = 99999;
        $updateData = [
            'name' => 'Some Name',
            'email' => 'some@example.com',
        ];

        // Act
        $result = SupplierPic::updateSupplierPIC($nonExistentId, $updateData);

        // Assert
        $this->assertEquals('error', $result['status']);
        $this->assertEquals(404, $result['code']);
        $this->assertStringContainsString('tidak ditemukan', $result['message']);
    }

    /**
     * Test Case 3: Partial update only some fields returns success
     */
    public function test_updateSupplierPIC_with_partial_data_returns_success()
    {
        // Arrange
        $supplier = Supplier::factory()->create();
        $pic = SupplierPic::addSupplierPIC($supplier->supplier_id, [
            'name' => 'Original Name',
            'phone_number' => '08123456789',
            'email' => 'original@example.com',
            'active' => 1,
        ]);

        $partialUpdateData = [
            'name' => 'Partially Updated Name',
        ];

        // Act
        $result = SupplierPic::updateSupplierPIC($pic->id, $partialUpdateData);

        // Assert
        $this->assertEquals('success', $result['status']);
        $this->assertEquals(200, $result['code']);

        $table = config('db_tables.supplier_pic');
        $this->assertDatabaseHas($table, [
            'id' => $pic->id,
            'name' => 'Partially Updated Name',
            'phone_number' => '08123456789',
            'email' => 'original@example.com',
        ]);
    }
}
