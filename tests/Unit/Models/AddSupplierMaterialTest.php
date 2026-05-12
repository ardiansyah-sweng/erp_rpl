<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\SupplierMaterial;

class AddSupplierMaterialTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // run migrations so supplier_product table exists
        $this->artisan('migrate');
    }

    /**
     * Test successful creation with valid data
     */
    public function test_addSupplierMaterial_creates_record_with_valid_data()
    {
        // Arrange
        $data = [
            'supplier_id' => 'SUP001',
            'company_name' => 'PT Testing Supplier',
            'product_id' => 'PROD-01',
            'product_name' => 'Test Material',
            'base_price' => 10000,
        ];

        // Act
        $created = SupplierMaterial::addSupplierMaterial($data);

        // Assert
        $this->assertInstanceOf(SupplierMaterial::class, $created);
        $this->assertDatabaseHas('supplier_product', [
            'supplier_id' => 'SUP001',
            'product_id' => 'PROD-01',
            'company_name' => 'PT Testing Supplier',
        ]);
        $this->assertEquals(10000, $created->base_price);
    }

    /**
     * Test that passing empty data throws an exception
     */
    public function test_addSupplierMaterial_throws_exception_on_empty_data()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Data tidak boleh kosong.');

        // Act
        SupplierMaterial::addSupplierMaterial([]);
    }

    /**
     * Test that method accepts object input (will be cast to array)
     */
    public function test_addSupplierMaterial_accepts_object_input()
    {
        // Arrange
        $obj = (object) [
            'supplier_id' => 'SUPOBJ',
            'company_name' => 'PT Object Supplier',
            'product_id' => 'OBJ-01',
            'product_name' => 'Object Material',
            'base_price' => 20000,
        ];

        // Act
        $created = SupplierMaterial::addSupplierMaterial($obj);

        // Assert
        $this->assertInstanceOf(SupplierMaterial::class, $created);
        $this->assertDatabaseHas('supplier_product', [
            'supplier_id' => 'SUPOBJ',
            'product_id' => 'OBJ-01',
        ]);
        $this->assertEquals(20000, $created->base_price);
    }
}
