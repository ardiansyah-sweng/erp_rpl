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
}
