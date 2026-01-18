<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\SupplierPICModel;
use App\Models\Supplier;
use App\Constants\SupplierPicColumns;
use App\Constants\Messages;
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

    // ========== GET PIC BY ID METHOD TESTS ==========

    /**
     * TC-SPIC-01
     * Test: Get PIC by valid ID returns PIC detail page
     * 
     * Scenario: User requests PIC detail dengan ID yang valid
     * Expected: Halaman detail PIC ditampilkan dengan informasi lengkap
     */
    public function test_get_pic_by_valid_id_returns_detail_page()
    {
        // ARRANGE - Create test supplier and PIC
        $this->createSupplier('SUP001', 'Test Company');
        
        $pic = SupplierPICModel::create([
            SupplierPicColumns::SUPPLIER_ID => 'SUP001',
            SupplierPicColumns::NAME => 'John Doe',
            SupplierPicColumns::PHONE => '081234567890',
            SupplierPicColumns::EMAIL => 'john@example.com',
            SupplierPicColumns::IS_ACTIVE => true,
            SupplierPicColumns::ASSIGNED_DATE => '2024-01-15',
        ]);

        // ACT - Request PIC detail page
        $response = $this->get("/supplier/pic/detail/{$pic->id}");

        // ASSERT - Verify successful response
        $response->assertStatus(200);
        $response->assertViewIs('supplier.pic.detail');
        $response->assertViewHas('pic');
        $response->assertViewHas('supplier');
        
        // Verify view data contains correct PIC information
        $viewPic = $response->viewData('pic');
        $this->assertEquals($pic->id, $viewPic->id);
        $this->assertEquals('John Doe', $viewPic->name);
        $this->assertEquals('081234567890', $viewPic->phone_number);
        $this->assertEquals('john@example.com', $viewPic->email);
        $this->assertEquals('Test Company', $viewPic->supplier_name);
    }

    /**
     * TC-SPIC-02
     * Test: Get PIC by invalid ID redirects with error
     * 
     * Scenario: User requests PIC detail dengan ID yang tidak ada
     * Expected: Redirect ke halaman supplier dengan error message
     */
    public function test_get_pic_by_invalid_id_redirects_with_error()
    {
        // ARRANGE - No PIC created, use non-existent ID
        $invalidId = 99999;

        // ACT - Request non-existent PIC detail
        $response = $this->get("/supplier/pic/detail/{$invalidId}");

        // ASSERT - Verify redirect with error
        $response->assertStatus(302);
        $response->assertRedirect('/supplier');
        $response->assertSessionHas('error', Messages::SUPPLIER_PIC_NOT_FOUND);
    }

    /**
     * TC-SPIC-03
     * Test: Get PIC with supplier relationship loaded
     * 
     * Scenario: PIC detail harus menampilkan informasi supplier terkait
     * Expected: View contains both PIC and supplier information
     */
    public function test_get_pic_loads_supplier_relationship()
    {
        // ARRANGE - Create supplier and PIC
        $this->createSupplier('SUP002', 'ABC Corporation');
        
        $pic = SupplierPICModel::create([
            SupplierPicColumns::SUPPLIER_ID => 'SUP002',
            SupplierPicColumns::NAME => 'Jane Smith',
            SupplierPicColumns::PHONE => '081987654321',
            SupplierPicColumns::EMAIL => 'jane@abc.com',
            SupplierPicColumns::IS_ACTIVE => true,
            SupplierPicColumns::ASSIGNED_DATE => '2024-02-01',
        ]);

        // ACT - Request PIC detail
        $response = $this->get("/supplier/pic/detail/{$pic->id}");

        // ASSERT - Verify supplier data is loaded
        $response->assertStatus(200);
        $viewPic = $response->viewData('pic');
        $viewSupplier = $response->viewData('supplier');
        
        $this->assertNotNull($viewSupplier);
        $this->assertEquals('SUP002', $viewSupplier->supplier_id);
        $this->assertEquals('ABC Corporation', $viewSupplier->company_name);
        $this->assertEquals('ABC Corporation', $viewPic->supplier_name);
    }

    /**
     * TC-SPIC-04
     * Test: Get multiple PICs with different IDs
     * 
     * Scenario: Verify correct PIC is returned for each unique ID
     * Expected: Each request returns the correct PIC data
     */
    public function test_get_multiple_pics_by_different_ids()
    {
        // ARRANGE - Create supplier and multiple PICs
        $this->createSupplier('SUP003', 'Multi PIC Company');
        
        $pic1 = SupplierPICModel::create([
            SupplierPicColumns::SUPPLIER_ID => 'SUP003',
            SupplierPicColumns::NAME => 'First PIC',
            SupplierPicColumns::PHONE => '081111111111',
            SupplierPicColumns::EMAIL => 'first@example.com',
            SupplierPicColumns::IS_ACTIVE => true,
            SupplierPicColumns::ASSIGNED_DATE => '2024-01-01',
        ]);
        
        $pic2 = SupplierPICModel::create([
            SupplierPicColumns::SUPPLIER_ID => 'SUP003',
            SupplierPicColumns::NAME => 'Second PIC',
            SupplierPicColumns::PHONE => '082222222222',
            SupplierPicColumns::EMAIL => 'second@example.com',
            SupplierPicColumns::IS_ACTIVE => true,
            SupplierPicColumns::ASSIGNED_DATE => '2024-02-01',
        ]);

        // ACT - Request each PIC detail
        $response1 = $this->get("/supplier/pic/detail/{$pic1->id}");
        $response2 = $this->get("/supplier/pic/detail/{$pic2->id}");

        // ASSERT - Verify each response returns correct PIC
        $response1->assertStatus(200);
        $viewPic1 = $response1->viewData('pic');
        $this->assertEquals('First PIC', $viewPic1->name);
        $this->assertEquals('081111111111', $viewPic1->phone_number);
        
        $response2->assertStatus(200);
        $viewPic2 = $response2->viewData('pic');
        $this->assertEquals('Second PIC', $viewPic2->name);
        $this->assertEquals('082222222222', $viewPic2->phone_number);
    }

    /**
     * TC-SPIC-05
     * Test: Get PIC with string ID (boundary test)
     * 
     * Scenario: Test behavior with non-numeric ID
     * Expected: Redirect with error message
     */
    public function test_get_pic_with_string_id()
    {
        // ARRANGE - Use string ID
        $stringId = 'invalid-string-id';

        // ACT - Request PIC with string ID
        $response = $this->get("/supplier/pic/detail/{$stringId}");

        // ASSERT - Verify redirect with error
        $response->assertStatus(302);
        $response->assertRedirect('/supplier');
        $response->assertSessionHas('error', Messages::SUPPLIER_PIC_NOT_FOUND);
    }

    /**
     * TC-SPIC-06
     * Test: Get PIC with negative ID (boundary test)
     * 
     * Scenario: Test behavior with negative ID
     * Expected: Redirect with error message
     */
    public function test_get_pic_with_negative_id()
    {
        // ARRANGE - Use negative ID
        $negativeId = -1;

        // ACT - Request PIC with negative ID
        $response = $this->get("/supplier/pic/detail/{$negativeId}");

        // ASSERT - Verify redirect with error
        $response->assertStatus(302);
        $response->assertRedirect('/supplier');
        $response->assertSessionHas('error', Messages::SUPPLIER_PIC_NOT_FOUND);
    }

    /**
     * TC-SPIC-07
     * Test: Get PIC with zero ID (boundary test)
     * 
     * Scenario: Test behavior with ID = 0
     * Expected: Redirect with error message
     */
    public function test_get_pic_with_zero_id()
    {
        // ARRANGE - Use zero ID
        $zeroId = 0;

        // ACT - Request PIC with zero ID
        $response = $this->get("/supplier/pic/detail/{$zeroId}");

        // ASSERT - Verify redirect with error
        $response->assertStatus(302);
        $response->assertRedirect('/supplier');
        $response->assertSessionHas('error', Messages::SUPPLIER_PIC_NOT_FOUND);
    }

    /**
     * TC-SPIC-08
     * Test: Get PIC by ID calls model method correctly
     * 
     * Scenario: Verify controller uses SupplierPic::getPICByID() method
     * Expected: Model method is called with correct parameter
     */
    public function test_get_pic_by_id_calls_model_method()
    {
        // ARRANGE - Create test data
        $this->createSupplier('SUP004', 'Method Test Company');
        
        $pic = SupplierPICModel::create([
            SupplierPicColumns::SUPPLIER_ID => 'SUP004',
            SupplierPicColumns::NAME => 'Method Test PIC',
            SupplierPicColumns::PHONE => '083333333333',
            SupplierPicColumns::EMAIL => 'method@example.com',
            SupplierPicColumns::IS_ACTIVE => true,
            SupplierPicColumns::ASSIGNED_DATE => '2024-04-01',
        ]);

        // ACT - Request PIC detail
        $response = $this->get("/supplier/pic/detail/{$pic->id}");

        // ASSERT - Verify model returns correct data
        // This implicitly tests that getPICByID() is working
        $response->assertStatus(200);
        $viewPic = $response->viewData('pic');
        $this->assertEquals($pic->id, $viewPic->id);
        $this->assertEquals('Method Test PIC', $viewPic->name);
    }

    /**
     * TC-SPIC-09
     * Test: Get PIC detail with all fields populated
     * 
     * Scenario: Verify all PIC fields are correctly displayed
     * Expected: All fields (id, name, phone, email, etc.) are present in view
     */
    public function test_get_pic_detail_with_all_fields()
    {
        // ARRANGE - Create PIC with all fields
        $this->createSupplier('SUP005', 'Complete Data Company');
        
        $pic = SupplierPICModel::create([
            SupplierPicColumns::SUPPLIER_ID => 'SUP005',
            SupplierPicColumns::NAME => 'Complete PIC',
            SupplierPicColumns::PHONE => '084444444444',
            SupplierPicColumns::EMAIL => 'complete@example.com',
            SupplierPicColumns::IS_ACTIVE => true,
            SupplierPicColumns::ASSIGNED_DATE => '2024-05-01',
        ]);

        // ACT - Request PIC detail
        $response = $this->get("/supplier/pic/detail/{$pic->id}");

        // ASSERT - Verify all fields are present
        $response->assertStatus(200);
        $viewPic = $response->viewData('pic');
        
        $this->assertNotNull($viewPic->id);
        $this->assertNotNull($viewPic->supplier_id);
        $this->assertNotNull($viewPic->name);
        $this->assertNotNull($viewPic->phone_number);
        $this->assertNotNull($viewPic->email);
        $this->assertNotNull($viewPic->is_active);
        $this->assertNotNull($viewPic->assigned_date);
        
        // Verify specific values
        $this->assertEquals('Complete PIC', $viewPic->name);
        $this->assertEquals('084444444444', $viewPic->phone_number);
        $this->assertEquals('complete@example.com', $viewPic->email);
        $this->assertEquals(1, $viewPic->is_active); // Database returns 1 for true
        $this->assertEquals('2024-05-01', $viewPic->assigned_date);
    }

    
}