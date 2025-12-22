<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\Merk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;

class MerkSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup konfigurasi database untuk testing
        config(['db_constants.table.merk' => 'merks']);
        config(['db_constants.column.merk' => [
            'id', 'merk', 'is_active', 'created_at', 'updated_at'
        ]]);
    }

    /**
     * @test
     * @group merk-search
     */
    public function searchMerk_returns_paginated_results()
    {
        // Setup data
        for ($i = 1; $i <= 3; $i++) {
            $merk = new Merk();
            $merk->id = 'merk-' . str_pad($i, 3, '0', STR_PAD_LEFT);
            $merk->merk = 'Merk Test ' . $i;
            $merk->is_active = 1;
            $merk->save();
        }

        // Execute
        $results = Merk::searchMerk('Test');

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $results);
        $this->assertEquals(10, $results->perPage());
    }

    /**
     * @test
     * @group merk-search
     */
    public function searchMerk_finds_records_with_matching_keyword()
    {
        // Setup
        $merk1 = new Merk();
        $merk1->id = 'merk-001';
        $merk1->merk = 'Samsung Galaxy';
        $merk1->is_active = 1;
        $merk1->save();

        $merk2 = new Merk();
        $merk2->id = 'merk-002';
        $merk2->merk = 'Apple iPhone';
        $merk2->is_active = 1;
        $merk2->save();

        // Execute
        $results = Merk::searchMerk('Samsung');

        // Assert
        $this->assertCount(1, $results->items());
        $this->assertEquals('Samsung Galaxy', $results->first()->merk);
        $this->assertEquals('merk-001', $results->first()->id);
    }

    /**
     * @test
     * @group merk-search
     */
    public function searchMerk_returns_empty_when_no_match()
    {
        // Setup
        $merk = new Merk();
        $merk->id = 'merk-001';
        $merk->merk = 'Samsung';
        $merk->is_active = 1;
        $merk->save();

        // Execute
        $results = Merk::searchMerk('Apple');

        // Assert
        $this->assertCount(0, $results->items());
        $this->assertEmpty($results->items());
    }

    /**
     * @test
     * @group merk-search
     */
    public function searchMerk_performs_partial_match_search()
    {
        // Setup
        $merk = new Merk();
        $merk->id = 'merk-001';
        $merk->merk = 'Samsung Galaxy S21 Ultra';
        $merk->is_active = 1;
        $merk->save();

        // Execute - test beberapa partial keyword
        $testCases = [
            'Samsung' => 1,
            'Galaxy' => 1,
            'S21' => 1,
            'Ultra' => 1,
            'Samsung Galaxy' => 1,
            'xyz' => 0, // tidak ada
        ];

        foreach ($testCases as $keyword => $expectedCount) {
            $results = Merk::searchMerk($keyword);
            $this->assertCount($expectedCount, $results->items(), 
                "Failed for keyword: '{$keyword}' - Expected: {$expectedCount}, Actual: " . count($results->items()));
        }
    }

    /**
     * @test
     * @group merk-search
     */
    public function searchMerk_is_case_insensitive()
    {
        // Setup
        $merk = new Merk();
        $merk->id = 'merk-001';
        $merk->merk = 'SAMSUNG Galaxy';
        $merk->is_active = 1;
        $merk->save();

        // Execute - test berbagai case
        $testCases = ['SAMSUNG', 'samsung', 'Samsung', 'SaMsUnG'];

        foreach ($testCases as $keyword) {
            $results = Merk::searchMerk($keyword);
            $this->assertCount(1, $results->items(), 
                "Case insensitive failed for keyword: '{$keyword}'");
        }
    }

    /**
     * @test
     * @group merk-search
     */
    public function searchMerk_orders_results_by_created_at_ascending()
    {
        // Setup - buat dengan tanggal berbeda
        $merk1 = new Merk();
        $merk1->id = 'merk-001';
        $merk1->merk = 'Brand C';
        $merk1->is_active = 1;
        $merk1->created_at = '2023-03-01 10:00:00';
        $merk1->save();

        $merk2 = new Merk();
        $merk2->id = 'merk-002';
        $merk2->merk = 'Brand A';
        $merk2->is_active = 1;
        $merk2->created_at = '2023-01-01 10:00:00';
        $merk2->save();

        $merk3 = new Merk();
        $merk3->id = 'merk-003';
        $merk3->merk = 'Brand B';
        $merk3->is_active = 1;
        $merk3->created_at = '2023-02-01 10:00:00';
        $merk3->save();

        // Execute
        $results = Merk::searchMerk('Brand');

        // Assert - harus urut berdasarkan created_at ascending
        $items = $results->items();
        $this->assertEquals('Brand A', $items[0]->merk); // created_at paling awal
        $this->assertEquals('Brand B', $items[1]->merk);
        $this->assertEquals('Brand C', $items[2]->merk); // created_at paling akhir
    }

    /**
     * @test
     * @group merk-search
     */
    public function searchMerk_handles_empty_keyword()
    {
        // Setup
        for ($i = 1; $i <= 5; $i++) {
            $merk = new Merk();
            $merk->id = 'merk-' . str_pad($i, 3, '0', STR_PAD_LEFT);
            $merk->merk = 'Brand ' . $i;
            $merk->is_active = 1;
            $merk->save();
        }

        // Execute
        $results = Merk::searchMerk('');

        // Assert - empty keyword should return all records
        $this->assertCount(5, $results->items());
    }

    /**
     * @test
     * @group merk-search
     */
    public function searchMerk_handles_null_keyword()
    {
        // Setup
        for ($i = 1; $i <= 3; $i++) {
            $merk = new Merk();
            $merk->id = 'merk-' . str_pad($i, 3, '0', STR_PAD_LEFT);
            $merk->merk = 'Brand ' . $i;
            $merk->is_active = 1;
            $merk->save();
        }

        // Execute
        $results = Merk::searchMerk(null);

        // Assert - null keyword should return all records
        $this->assertCount(3, $results->items());
    }

    /**
     * @test
     * @group merk-search
     */
    public function searchMerk_handles_special_characters_in_keyword()
    {
        // Setup
        $merk = new Merk();
        $merk->id = 'merk-001';
        $merk->merk = 'iPhone 13 Pro Max';
        $merk->is_active = 1;
        $merk->save();

        // Execute - test dengan special characters
        $testCases = [
            '13 Pro' => 1,
            'Pro Max' => 1,
            'iPhone 13' => 1,
        ];

        foreach ($testCases as $keyword => $expectedCount) {
            $results = Merk::searchMerk($keyword);
            $this->assertCount($expectedCount, $results->items(),
                "Failed for keyword with special chars: '{$keyword}'");
        }
    }

    /**
     * @test
     * @group merk-search
     */
    public function searchMerk_returns_correct_pagination_metadata()
    {
        // Setup - buat 15 data
        for ($i = 1; $i <= 15; $i++) {
            $merk = new Merk();
            $merk->id = 'merk-' . str_pad($i, 3, '0', STR_PAD_LEFT);
            $merk->merk = 'Test Brand ' . $i;
            $merk->is_active = 1;
            $merk->save();
        }

        // Execute
        $results = Merk::searchMerk('Test');

        // Assert pagination metadata
        $this->assertEquals(10, $results->perPage());
        $this->assertEquals(15, $results->total());
        $this->assertEquals(2, $results->lastPage());
        $this->assertEquals(1, $results->currentPage());
        $this->assertCount(10, $results->items()); // Page 1 has 10 items
    }

    /**
     * @test
     * @group merk-search
     */
    public function searchMerk_handles_whitespace_in_keyword()
    {
        // Setup
        $merk = new Merk();
        $merk->id = 'merk-001';
        $merk->merk = 'Samsung Galaxy S21';
        $merk->is_active = 1;
        $merk->save();

        // Execute - test dengan whitespace
        $results = Merk::searchMerk('  Samsung  '); // dengan extra spaces

        // Assert
        $this->assertCount(1, $results->items());
    }

    /**
     * @test
     * @group merk-search
     */
    public function searchMerk_finds_multiple_matching_records()
    {
        // Setup
        $data = [
            ['id' => 'merk-001', 'merk' => 'Apple iPhone'],
            ['id' => 'merk-002', 'merk' => 'Apple Watch'],
            ['id' => 'merk-003', 'merk' => 'Samsung Galaxy'],
            ['id' => 'merk-004', 'merk' => 'Apple iPad'],
        ];

        foreach ($data as $item) {
            $merk = new Merk();
            $merk->id = $item['id'];
            $merk->merk = $item['merk'];
            $merk->is_active = 1;
            $merk->save();
        }

        // Execute
        $results = Merk::searchMerk('Apple');

        // Assert
        $this->assertCount(3, $results->items());
        
        // Verify all results contain 'Apple'
        foreach ($results->items() as $item) {
            $this->assertStringContainsStringIgnoringCase('apple', $item->merk);
        }
    }
}