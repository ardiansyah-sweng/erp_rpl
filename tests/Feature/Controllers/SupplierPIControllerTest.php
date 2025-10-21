<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\SupplierPICModel;
use App\Models\Supplier;
use App\Constants\SupplierPicColumns;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;

class SupplierPIControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup database tables
        $this->artisan('migrate');
    }

    /**
     * Helper method to create supplier using DB::table to bypass fillable
     */
    private function createSupplier($supplierId, $companyName = 'Test Company')
    {
        DB::table('suppliers')->insert([
            'supplier_id' => $supplierId,
            'company_name' => $companyName,
            'address' => 'Jl. Test',
            'telephone' => '021-1111111',
            'bank_account' => '1234567890',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Test searchSupplierPic() without keywords parameter
     */
    public function test_search_supplier_pic_returns_all_supplier_pics()
    {
        // Arrange - Create test supplier using DB::table
        $this->createSupplier('SUP001');

        // Create test supplier PICs
        $pic1 = SupplierPICModel::create([
            SupplierPicColumns::SUPPLIER_ID => 'SUP001',
            SupplierPicColumns::NAME => 'John Doe',
            SupplierPicColumns::PHONE => '081234567890',
            SupplierPicColumns::EMAIL => 'john@example.com',
            SupplierPicColumns::IS_ACTIVE => true,
            SupplierPicColumns::ASSIGNED_DATE => '2024-01-15',
        ]);

        $pic2 = SupplierPICModel::create([
            SupplierPicColumns::SUPPLIER_ID => 'SUP001',
            SupplierPicColumns::NAME => 'Jane Smith',
            SupplierPicColumns::PHONE => '081987654321',
            SupplierPicColumns::EMAIL => 'jane@example.com',
            SupplierPicColumns::IS_ACTIVE => true,
            SupplierPicColumns::ASSIGNED_DATE => '2024-01-20',
        ]);

        // Act - Call searchSupplierPic without keywords
        $result = SupplierPICModel::searchSupplierPic();

        // Assert - Should return paginated results with all supplier PICs
        $this->assertNotNull($result);
        $this->assertEquals(2, $result->total());
        $this->assertCount(2, $result->items());
        
        // Assert data contains created supplier PICs
        $picNames = $result->pluck(SupplierPicColumns::NAME)->toArray();
        $this->assertContains('John Doe', $picNames);
        $this->assertContains('Jane Smith', $picNames);
    }

    /**
     * Test searchSupplierPic() with search parameter - search by name
     */
    public function test_search_supplier_pic_with_search_by_name()
    {
        // Arrange - Create test supplier
        $this->createSupplier('SUP001');

        // Create test supplier PICs
        SupplierPICModel::create([
            SupplierPicColumns::SUPPLIER_ID => 'SUP001',
            SupplierPicColumns::NAME => 'Ahmad Wijaya',
            SupplierPicColumns::PHONE => '081234567890',
            SupplierPicColumns::EMAIL => 'ahmad@example.com',
            SupplierPicColumns::IS_ACTIVE => true,
            SupplierPicColumns::ASSIGNED_DATE => '2024-01-15',
        ]);

        SupplierPICModel::create([
            SupplierPicColumns::SUPPLIER_ID => 'SUP001',
            SupplierPicColumns::NAME => 'Budi Santoso',
            SupplierPicColumns::PHONE => '081987654321',
            SupplierPicColumns::EMAIL => 'budi@example.com',
            SupplierPicColumns::IS_ACTIVE => true,
            SupplierPicColumns::ASSIGNED_DATE => '2024-01-20',
        ]);

        // Act - Search by name containing 'Ahmad'
        $result = SupplierPICModel::searchSupplierPic('Ahmad');

        // Assert - Should return only supplier PICs with 'Ahmad' in name
        $this->assertNotNull($result);
        $this->assertEquals(1, $result->total());
        $this->assertCount(1, $result->items());
        $this->assertStringContainsString('Ahmad', $result->first()->name);
    }

    /**
     * Test searchSupplierPic() with search parameter - search by phone_number
     */
    public function test_search_supplier_pic_with_search_by_phone()
    {
        // Arrange - Create test supplier
        $this->createSupplier('SUP001');

        // Create test supplier PICs
        SupplierPICModel::create([
            SupplierPicColumns::SUPPLIER_ID => 'SUP001',
            SupplierPicColumns::NAME => 'John Doe',
            SupplierPicColumns::PHONE => '081234567890',
            SupplierPicColumns::EMAIL => 'john@example.com',
            SupplierPicColumns::IS_ACTIVE => true,
            SupplierPicColumns::ASSIGNED_DATE => '2024-01-15',
        ]);

        SupplierPICModel::create([
            SupplierPicColumns::SUPPLIER_ID => 'SUP001',
            SupplierPicColumns::NAME => 'Jane Smith',
            SupplierPicColumns::PHONE => '081987654321',
            SupplierPicColumns::EMAIL => 'jane@example.com',
            SupplierPicColumns::IS_ACTIVE => true,
            SupplierPicColumns::ASSIGNED_DATE => '2024-01-20',
        ]);

        // Act - Search by phone containing '081234'
        $result = SupplierPICModel::searchSupplierPic('081234');

        // Assert - Should return only supplier PICs with '081234' in phone
        $this->assertNotNull($result);
        $this->assertEquals(1, $result->total());
        $this->assertCount(1, $result->items());
        $this->assertStringContainsString('081234', $result->first()->phone_number);
    }

    /**
     * Test searchSupplierPic() with search parameter - search by email
     */
    public function test_search_supplier_pic_with_search_by_email()
    {
        // Arrange - Create test supplier
        $this->createSupplier('SUP001');

        // Create test supplier PICs
        SupplierPICModel::create([
            SupplierPicColumns::SUPPLIER_ID => 'SUP001',
            SupplierPicColumns::NAME => 'John Doe',
            SupplierPicColumns::PHONE => '081234567890',
            SupplierPicColumns::EMAIL => 'john@company.com',
            SupplierPicColumns::IS_ACTIVE => true,
            SupplierPicColumns::ASSIGNED_DATE => '2024-01-15',
        ]);

        SupplierPICModel::create([
            SupplierPicColumns::SUPPLIER_ID => 'SUP001',
            SupplierPicColumns::NAME => 'Jane Smith',
            SupplierPicColumns::PHONE => '081987654321',
            SupplierPicColumns::EMAIL => 'jane@supplier.com',
            SupplierPicColumns::IS_ACTIVE => true,
            SupplierPicColumns::ASSIGNED_DATE => '2024-01-20',
        ]);

        // Act - Search by email containing 'company'
        $result = SupplierPICModel::searchSupplierPic('company');

        // Assert - Should return only supplier PICs with 'company' in email
        $this->assertNotNull($result);
        $this->assertEquals(1, $result->total());
        $this->assertCount(1, $result->items());
        $this->assertStringContainsString('company', $result->first()->email);
    }

    /**
     * Test searchSupplierPic() with search that returns no results
     */
    public function test_search_supplier_pic_with_search_no_results()
    {
        // Arrange - Create test supplier and PIC
        $this->createSupplier('SUP001');

        SupplierPICModel::create([
            SupplierPicColumns::SUPPLIER_ID => 'SUP001',
            SupplierPicColumns::NAME => 'Test PIC',
            SupplierPicColumns::PHONE => '081234567890',
            SupplierPicColumns::EMAIL => 'test@example.com',
            SupplierPicColumns::IS_ACTIVE => true,
            SupplierPicColumns::ASSIGNED_DATE => '2024-01-15',
        ]);

        // Act - Search with keyword that doesn't exist
        $result = SupplierPICModel::searchSupplierPic('NonExistentKeyword');

        // Assert - Should return empty paginated result
        $this->assertNotNull($result);
        $this->assertEquals(0, $result->total());
        $this->assertCount(0, $result->items());
    }

    /**
     * Test searchSupplierPic() returns paginated results
     */
    public function test_search_supplier_pic_returns_paginated_results()
    {
        // Arrange - Create test supplier
        $this->createSupplier('SUP001');

        // Create multiple supplier PICs
        for ($i = 1; $i <= 15; $i++) {
            SupplierPICModel::create([
                SupplierPicColumns::SUPPLIER_ID => 'SUP001',
                SupplierPicColumns::NAME => "PIC {$i}",
                SupplierPicColumns::PHONE => "08123456{$i}",
                SupplierPicColumns::EMAIL => "pic{$i}@example.com",
                SupplierPicColumns::IS_ACTIVE => true,
                SupplierPicColumns::ASSIGNED_DATE => '2024-01-15',
            ]);
        }

        // Act - Get paginated results
        $result = SupplierPICModel::searchSupplierPic();

        // Assert - Should return paginated results with 10 items per page
        $this->assertNotNull($result);
        $this->assertEquals(15, $result->total());
        $this->assertEquals(10, $result->perPage());
        
        // Assert pagination metadata exists
        $this->assertNotNull($result->currentPage());
        $this->assertNotNull($result->lastPage());
    }

    /**
     * Test searchSupplierPic() ordering - should be ordered by created_at asc
     */
    public function test_search_supplier_pic_ordering()
    {
        // Arrange - Create test supplier
        $this->createSupplier('SUP001');

        // Create supplier PICs with different timestamps
        $pic1 = SupplierPICModel::create([
            SupplierPicColumns::SUPPLIER_ID => 'SUP001',
            SupplierPicColumns::NAME => 'First PIC',
            SupplierPicColumns::PHONE => '081234567890',
            SupplierPicColumns::EMAIL => 'first@example.com',
            SupplierPicColumns::IS_ACTIVE => true,
            SupplierPicColumns::ASSIGNED_DATE => '2024-01-15',
        ]);

        // Small delay to ensure different timestamps
        sleep(1);

        $pic2 = SupplierPICModel::create([
            SupplierPicColumns::SUPPLIER_ID => 'SUP001',
            SupplierPicColumns::NAME => 'Second PIC',
            SupplierPicColumns::PHONE => '081987654321',
            SupplierPicColumns::EMAIL => 'second@example.com',
            SupplierPicColumns::IS_ACTIVE => true,
            SupplierPicColumns::ASSIGNED_DATE => '2024-01-20',
        ]);

        // Act - Get all supplier PICs
        $result = SupplierPICModel::searchSupplierPic();

        // Assert - Should be ordered by created_at ascending (oldest first)
        $this->assertNotNull($result);
        $this->assertEquals(2, $result->total());
        $this->assertEquals('First PIC', $result->first()->name);
        $this->assertEquals('Second PIC', $result->last()->name);
    }
}