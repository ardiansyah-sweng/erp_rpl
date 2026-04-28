<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Supplier;
use App\Models\SupplierPic;
use Database\Factories\SupplierPICFactory;

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

    public function test_searchSupplierPic_with_null_keywords_returns_all_data()
    {
        // Arrange
        SupplierPICFactory::new()->count(15)->create();

        // Act
        $result = SupplierPic::searchSupplierPic(null);

        // Assert
        $this->assertEquals(15, $result->total());
        $this->assertCount(10, $result->items());
    }

    public function test_searchSupplierPic_by_name()
    {
        // Arrange
        SupplierPICFactory::new()->withName('John Doe')->create();
        SupplierPICFactory::new()->count(5)->create();

        // Act
        $result = SupplierPic::searchSupplierPic('John');

        // Assert
        $this->assertGreaterThan(0, $result->total());
        $found = false;
        foreach ($result->items() as $item) {
            if (strpos($item->name, 'John') !== false) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found);
    }

    public function test_searchSupplierPic_no_matching_data()
    {
        // Arrange
        SupplierPICFactory::new()->count(5)->create();

        // Act
        $result = SupplierPic::searchSupplierPic('NONEXISTENT');

        // Assert
        $this->assertEquals(0, $result->total());
        $this->assertCount(0, $result->items());
    }
}