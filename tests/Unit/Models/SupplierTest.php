<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Supplier;

class SupplierTest extends TestCase
{
    use RefreshDatabase;

    // Test Case 1: Update Supplier with valid data returns the updated model
    public function test_updateSupplier_with_valid_data_returns_updated_model()
    {
        // Arrange: Create supplier
        $supplier = Supplier::factory()->create([
            'company_name' => 'Original Company',
            'address' => 'Original Address',
            'telephone' => '08123456789',
        ]);

        $updateData = [
            'company_name' => 'Updated Company',
            'address' => 'Updated Address',
            'telephone' => '08987654321',
        ];

        // Act
        $result = Supplier::updateSupplier($supplier->supplier_id, $updateData);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(Supplier::class, $result);
        $this->assertEquals('Updated Company', $result->company_name);
        $this->assertEquals('Updated Address', $result->address);
        $this->assertEquals('08987654321', $result->telephone);

        $table = config('db_tables.supplier');
        $this->assertDatabaseHas($table, [
            'supplier_id' => $supplier->supplier_id,
            'company_name' => 'Updated Company',
            'address' => 'Updated Address',
            'telephone' => '08987654321',
        ]);
    }

    // Test Case 2: Update non-existent Supplier returns null
    public function test_updateSupplier_with_non_existent_id_returns_null()
    {
        // Arrange
        $nonExistentId = 'SUPPLIER-99999';
        $updateData = [
            'company_name' => 'Some Company',
            'address' => 'Some Address',
        ];

        // Act
        $result = Supplier::updateSupplier($nonExistentId, $updateData);

        // Assert
        $this->assertNull($result);
    }

    // Test Case 3: Partial update only some fields returns updated model
    public function test_updateSupplier_with_partial_data_returns_updated_model()
    {
        // Arrange: Create supplier
        $supplier = Supplier::factory()->create([
            'company_name' => 'Original Company',
            'address' => 'Original Address',
            'telephone' => '08123456789',
        ]);

        $partialUpdateData = [
            'company_name' => 'Partially Updated Company',
        ];

        // Act
        $result = Supplier::updateSupplier($supplier->supplier_id, $partialUpdateData);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(Supplier::class, $result);
        $this->assertEquals('Partially Updated Company', $result->company_name);
        $this->assertEquals('Original Address', $result->address);  // Unchanged
        $this->assertEquals('08123456789', $result->telephone);    // Unchanged

        $table = config('db_tables.supplier');
        $this->assertDatabaseHas($table, [
            'supplier_id' => $supplier->supplier_id,
            'company_name' => 'Partially Updated Company',
            'address' => 'Original Address',  // Unchanged
            'telephone' => '08123456789',    // Unchanged
        ]);
    }
}