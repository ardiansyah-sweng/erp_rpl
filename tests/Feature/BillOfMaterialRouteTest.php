<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\BillOfMaterial;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BillOfMaterialRouteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test 1: Successful response with data.
     * Verifies that the endpoint returns 200 status with proper JSON structure
     * and paginated data when BillOfMaterial records exist.
     *
     * @return void
     */
    public function test_get_bill_of_material_returns_successful_response_with_data()
    {
        // Arrange - Create 3 BillOfMaterial records
        DB::table('bill_of_material')->insert([
            [
                'bom_id' => 'BOM001',
                'bom_name' => 'Test BOM 1',
                'measurement_unit' => 'Unit',
                'total_cost' => 1000,
                'active' => 1,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'bom_id' => 'BOM002',
                'bom_name' => 'Test BOM 2',
                'measurement_unit' => 'Kg',
                'total_cost' => 2000,
                'active' => 1,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'bom_id' => 'BOM003',
                'bom_name' => 'Test BOM 3',
                'measurement_unit' => 'Liter',
                'total_cost' => 3000,
                'active' => 0,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
        ]);

        // Act - Call the getBillOfMaterial endpoint
        $response = $this->get('/bill-of-material');

        // Assert - Verify response status and structure
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'bom_id',
                    'bom_name',
                    'measurement_unit',
                    'total_cost',
                    'active',
                    'created_at',
                    'updated_at'
                ]
            ],
            'current_page',
            'first_page_url',
            'from',
            'last_page',
            'last_page_url',
            'links',
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total'
        ]);

        // Verify correct number of records
        $this->assertCount(3, $response->json('data'));

        // Verify specific data
        $this->assertEquals('BOM001', $response->json('data.0.bom_id'));
        $this->assertEquals('Test BOM 1', $response->json('data.0.bom_name'));
        $this->assertEquals(1000, $response->json('data.0.total_cost'));
    }

    /**
     * Test 2: Empty database returns empty paginated response.
     * Verifies that when no BillOfMaterial records exist, the endpoint
     * returns 200 status with empty data array and total of 0.
     *
     * @return void
     */
    public function test_get_bill_of_material_returns_empty_response_when_no_data()
    {
        // Arrange - Ensure database is empty (RefreshDatabase already does this)
        $this->assertDatabaseCount('bill_of_material', 0);

        // Act - Call the getBillOfMaterial endpoint
        $response = $this->get('/bill-of-material');

        // Assert - Verify response structure with empty data
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [],
            'total' => 0,
            'current_page' => 1,
            'per_page' => 10
        ]);

        // Verify data array is empty
        $this->assertCount(0, $response->json('data'));
        $this->assertEquals(0, $response->json('total'));
        $this->assertNull($response->json('next_page_url'));
        $this->assertNull($response->json('prev_page_url'));
    }

    /**
     * Test 3: Pagination with multiple pages.
     * Verifies that pagination works correctly when there are more than
     * 10 records, properly splitting data across multiple pages.
     *
     * @return void
     */
    public function test_get_bill_of_material_returns_paginated_data_with_multiple_pages()
    {
        // Arrange - Create 15 BillOfMaterial records
        for ($i = 1; $i <= 15; $i++) {
            DB::table('bill_of_material')->insert([
                'bom_id' => 'BOM' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'bom_name' => 'Test BOM ' . $i,
                'measurement_unit' => 'Unit',
                'total_cost' => 1000 * $i,
                'active' => 1,
                'created_at' => now()->subDays(15 - $i),
                'updated_at' => now()->subDays(15 - $i),
            ]);
        }

        // Act - Call first page
        $responsePage1 = $this->get('/bill-of-material?page=1');

        // Assert - Verify first page contains 10 items
        $responsePage1->assertStatus(200);
        $this->assertCount(10, $responsePage1->json('data'));
        $this->assertEquals(1, $responsePage1->json('current_page'));
        $this->assertEquals(2, $responsePage1->json('last_page'));
        $this->assertEquals(10, $responsePage1->json('per_page'));
        $this->assertEquals(15, $responsePage1->json('total'));
        $this->assertNotNull($responsePage1->json('next_page_url'));
        $this->assertNull($responsePage1->json('prev_page_url'));

        // Act - Call second page
        $responsePage2 = $this->get('/bill-of-material?page=2');

        // Assert - Verify second page contains remaining 5 items
        $responsePage2->assertStatus(200);
        $this->assertCount(5, $responsePage2->json('data'));
        $this->assertEquals(2, $responsePage2->json('current_page'));
        $this->assertEquals(2, $responsePage2->json('last_page'));
        $this->assertNull($responsePage2->json('next_page_url'));
        $this->assertNotNull($responsePage2->json('prev_page_url'));
    }

    /**
     * Test 4: Response JSON structure completeness.
     * Verifies that all required fields are present in the response,
     * including all BillOfMaterial attributes and pagination metadata.
     *
     * @return void
     */
    public function test_get_bill_of_material_returns_complete_json_structure()
    {
        // Arrange - Create one BillOfMaterial record
        DB::table('bill_of_material')->insert([
            'bom_id' => 'BOM001',
            'bom_name' => 'Complete Test BOM',
            'measurement_unit' => 'Unit',
            'total_cost' => 5000,
            'active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Act - Call the getBillOfMaterial endpoint
        $response = $this->get('/bill-of-material');

        // Assert - Verify complete JSON structure
        $response->assertStatus(200);
        
        // Verify all data fields are present
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'bom_id',
                    'bom_name',
                    'measurement_unit',
                    'total_cost',
                    'active',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);

        // Verify all pagination fields are present
        $this->assertArrayHasKey('current_page', $response->json());
        $this->assertArrayHasKey('first_page_url', $response->json());
        $this->assertArrayHasKey('from', $response->json());
        $this->assertArrayHasKey('last_page', $response->json());
        $this->assertArrayHasKey('last_page_url', $response->json());
        $this->assertArrayHasKey('links', $response->json());
        $this->assertArrayHasKey('next_page_url', $response->json());
        $this->assertArrayHasKey('path', $response->json());
        $this->assertArrayHasKey('per_page', $response->json());
        $this->assertArrayHasKey('prev_page_url', $response->json());
        $this->assertArrayHasKey('to', $response->json());
        $this->assertArrayHasKey('total', $response->json());

        // Verify data types
        $this->assertIsInt($response->json('data.0.id'));
        $this->assertIsString($response->json('data.0.bom_id'));
        $this->assertIsString($response->json('data.0.bom_name'));
        $this->assertIsInt($response->json('data.0.total_cost'));
    }

    /**
     * Test 5: Data ordering by created_at ascending.
     * Verifies that results are correctly ordered by created_at in ascending order
     * (oldest records first).
     *
     * @return void
     */
    public function test_get_bill_of_material_returns_data_ordered_by_created_at_ascending()
    {
        // Arrange - Create BillOfMaterial records with specific created_at timestamps
        DB::table('bill_of_material')->insert([
            [
                'bom_id' => 'BOM003',
                'bom_name' => 'Newest BOM',
                'measurement_unit' => 'Unit',
                'total_cost' => 3000,
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bom_id' => 'BOM001',
                'bom_name' => 'Oldest BOM',
                'measurement_unit' => 'Unit',
                'total_cost' => 1000,
                'active' => 1,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'bom_id' => 'BOM002',
                'bom_name' => 'Middle BOM',
                'measurement_unit' => 'Unit',
                'total_cost' => 2000,
                'active' => 1,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
        ]);

        // Act - Call the getBillOfMaterial endpoint
        $response = $this->get('/bill-of-material');

        // Assert - Verify data is ordered by created_at ascending
        $response->assertStatus(200);
        $data = $response->json('data');

        // First record should be the oldest (BOM001)
        $this->assertEquals('BOM001', $data[0]['bom_id']);
        $this->assertEquals('Oldest BOM', $data[0]['bom_name']);

        // Second record should be the middle one (BOM002)
        $this->assertEquals('BOM002', $data[1]['bom_id']);
        $this->assertEquals('Middle BOM', $data[1]['bom_name']);

        // Third record should be the newest (BOM003)
        $this->assertEquals('BOM003', $data[2]['bom_id']);
        $this->assertEquals('Newest BOM', $data[2]['bom_name']);

        // Verify timestamps are in ascending order
        $timestamp1 = strtotime($data[0]['created_at']);
        $timestamp2 = strtotime($data[1]['created_at']);
        $timestamp3 = strtotime($data[2]['created_at']);

        $this->assertLessThan($timestamp2, $timestamp1);
        $this->assertLessThan($timestamp3, $timestamp2);
    }

    /**
     * Test 6: Single record pagination.
     * Verifies that pagination works correctly when there is exactly one record,
     * ensuring proper pagination metadata with no next page.
     *
     * @return void
     */
    public function test_get_bill_of_material_returns_single_record_with_correct_pagination()
    {
        // Arrange - Create exactly one BillOfMaterial record
        DB::table('bill_of_material')->insert([
            'bom_id' => 'BOM001',
            'bom_name' => 'Single BOM',
            'measurement_unit' => 'Unit',
            'total_cost' => 1000,
            'active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Act - Call the getBillOfMaterial endpoint
        $response = $this->get('/bill-of-material');

        // Assert - Verify pagination for single record
        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals(1, $response->json('total'));
        $this->assertEquals(1, $response->json('current_page'));
        $this->assertEquals(1, $response->json('last_page'));
        $this->assertEquals(10, $response->json('per_page'));
        $this->assertEquals(1, $response->json('from'));
        $this->assertEquals(1, $response->json('to'));
        $this->assertNull($response->json('next_page_url'));
        $this->assertNull($response->json('prev_page_url'));

        // Verify the single record data
        $this->assertEquals('BOM001', $response->json('data.0.bom_id'));
        $this->assertEquals('Single BOM', $response->json('data.0.bom_name'));
    }

    /**
     * Test 7: Exactly 10 records (one full page).
     * Verifies pagination behavior when there are exactly 10 records,
     * ensuring single page response with no next page.
     *
     * @return void
     */
    public function test_get_bill_of_material_returns_exactly_one_full_page()
    {
        // Arrange - Create exactly 10 BillOfMaterial records
        for ($i = 1; $i <= 10; $i++) {
            DB::table('bill_of_material')->insert([
                'bom_id' => 'BOM' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'bom_name' => 'Test BOM ' . $i,
                'measurement_unit' => 'Unit',
                'total_cost' => 1000 * $i,
                'active' => 1,
                'created_at' => now()->subDays(10 - $i),
                'updated_at' => now()->subDays(10 - $i),
            ]);
        }

        // Act - Call the getBillOfMaterial endpoint
        $response = $this->get('/bill-of-material');

        // Assert - Verify exactly 10 records on one page
        $response->assertStatus(200);
        $this->assertCount(10, $response->json('data'));
        $this->assertEquals(10, $response->json('total'));
        $this->assertEquals(1, $response->json('current_page'));
        $this->assertEquals(1, $response->json('last_page'));
        $this->assertEquals(10, $response->json('per_page'));
        $this->assertEquals(1, $response->json('from'));
        $this->assertEquals(10, $response->json('to'));
        $this->assertNull($response->json('next_page_url'));
        $this->assertNull($response->json('prev_page_url'));

        // Verify all 10 records are present
        $data = $response->json('data');
        $this->assertEquals('BOM001', $data[0]['bom_id']);
        $this->assertEquals('BOM010', $data[9]['bom_id']);
    }

    /**
     * Test 8: Pagination metadata accuracy.
     * Verifies that pagination URLs and metadata are correctly generated
     * and contain proper values for navigation.
     *
     * @return void
     */
    public function test_get_bill_of_material_returns_accurate_pagination_metadata()
    {
        // Arrange - Create 25 BillOfMaterial records
        for ($i = 1; $i <= 25; $i++) {
            DB::table('bill_of_material')->insert([
                'bom_id' => 'BOM' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'bom_name' => 'Test BOM ' . $i,
                'measurement_unit' => 'Unit',
                'total_cost' => 1000 * $i,
                'active' => 1,
                'created_at' => now()->subDays(25 - $i),
                'updated_at' => now()->subDays(25 - $i),
            ]);
        }

        // Act - Call the getBillOfMaterial endpoint for page 2
        $response = $this->get('/bill-of-material?page=2');

        // Assert - Verify pagination metadata
        $response->assertStatus(200);
        $this->assertEquals(2, $response->json('current_page'));
        $this->assertEquals(3, $response->json('last_page'));
        $this->assertEquals(10, $response->json('per_page'));
        $this->assertEquals(25, $response->json('total'));
        $this->assertEquals(11, $response->json('from'));
        $this->assertEquals(20, $response->json('to'));

        // Verify pagination URLs
        $this->assertStringContainsString('page=3', $response->json('next_page_url'));
        $this->assertStringContainsString('page=1', $response->json('prev_page_url'));
        $this->assertStringContainsString('page=1', $response->json('first_page_url'));
        $this->assertStringContainsString('page=3', $response->json('last_page_url'));
        $this->assertStringContainsString('/bill-of-material', $response->json('path'));

        // Verify links array
        $this->assertIsArray($response->json('links'));
        $this->assertNotEmpty($response->json('links'));
    }
}
