<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Merk;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchMerkModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed sample data for testing
        Merk::create([
            'merk' => 'Toyota',
            'is_active' => true,
        ]);

        Merk::create([
            'merk' => 'Honda',
            'is_active' => true,
        ]);

        Merk::create([
            'merk' => 'Suzuki',
            'is_active' => false,
        ]);

        Merk::create([
            'merk' => 'Daihatsu',
            'is_active' => true,
        ]);

        Merk::create([
            'merk' => 'Mitsubishi',
            'is_active' => true,
        ]);
    }

    /**
     * Test search with keyword that matches single result
     */
    public function test_search_merk_with_single_match()
    {
        $result = Merk::search('Toyota')->get();
        
        $this->assertCount(1, $result);
        $this->assertEquals('Toyota', $result->first()->merk);
    }

    /**
     * Test search with keyword that matches multiple results
     */
    public function test_search_merk_with_multiple_matches()
    {
        $result = Merk::search('Su')->get();
        
        $this->assertCount(3, $result);
        $names = $result->pluck('merk')->toArray();
        $this->assertContains('Suzuki', $names);
    }

    /**
     * Test search with keyword that matches no results
     */
    public function test_search_merk_with_no_match()
    {
        $result = Merk::search('BMW')->get();
        
        $this->assertCount(0, $result);
    }

    /**
     * Test search is case insensitive
     */
    public function test_search_merk_case_insensitive()
    {
        $result = Merk::search('toyota')->get();
        
        $this->assertCount(1, $result);
        $this->assertEquals('Toyota', $result->first()->merk);
    }

    /**
     * Test search with null keyword returns all results
     */
    public function test_search_merk_with_null_keyword()
    {
        $result = Merk::search(null)->get();
        
        $this->assertCount(5, $result);
    }

    /**
     * Test search with empty string returns all results
     */
    public function test_search_merk_with_empty_string()
    {
        $result = Merk::search('')->get();
        
        $this->assertCount(5, $result);
    }

    /**
     * Test search with partial keyword
     */
    public function test_search_merk_with_partial_keyword()
    {
        $result = Merk::search('Mit')->get();
        
        $this->assertCount(1, $result);
        $this->assertEquals('Mitsubishi', $result->first()->merk);
    }

    /**
     * Test search with whitespace in keyword
     */
    public function test_search_merk_with_whitespace()
    {
        Merk::create([
            'merk' => 'Toyota Land Cruiser',
            'is_active' => true,
        ]);

        $result = Merk::search('Land Cruiser')->get();
        
        $this->assertTrue($result->count() > 0);
    }

    /**
     * Test search with special characters
     */
    public function test_search_merk_with_special_characters()
    {
        Merk::create([
            'merk' => 'BMW-X',
            'is_active' => true,
        ]);

        $result = Merk::search('BMW-X')->get();
        
        $this->assertCount(1, $result);
        $this->assertEquals('BMW-X', $result->first()->merk);
    }

    /**
     * Test search combined with active scope
     */
    public function test_search_merk_combined_with_active_scope()
    {
        $result = Merk::active()->search('Su')->get();
        
        $this->assertCount(2, $result);
        $names = $result->pluck('merk')->toArray();
        $this->assertContains('Daihatsu', $names);
        $this->assertContains('Mitsubishi', $names);
        $this->assertNotContains('Suzuki', $names);
    }
}
