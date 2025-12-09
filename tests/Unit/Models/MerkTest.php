<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Merk;
use App\Constants\MerkColumns;

class MerkTest extends TestCase
{
    use RefreshDatabase;

    // ========================================================================
    // TEST UNTUK FUNGSI getMerkById($id) 
    // ========================================================================

    /**
     * Test getMerkById returns correct merk when ID exists
     * @test
     */
    public function test_getMerkById_returns_correct_merk_when_id_exists()
    {
        // Arrange
        $merk = Merk::factory()->create([
            MerkColumns::MERK => 'Toyota',
            MerkColumns::IS_ACTIVE => true
        ]);

        // Act
        $merkInstance = new Merk();
        $result = $merkInstance->getMerkById($merk->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(Merk::class, $result);
        $this->assertEquals($merk->id, $result->id);
        $this->assertEquals('Toyota', $result->{MerkColumns::MERK});
    }

    /**
     * Test getMerkById returns null when ID does not exist
     * @test
     */
    public function test_getMerkById_returns_null_when_id_does_not_exist()
    {
        // Arrange
        $nonExistentId = 99999;

        // Act
        $merkInstance = new Merk();
        $result = $merkInstance->getMerkById($nonExistentId);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getMerkById returns merk with correct data structure
     * @test
     */
    public function test_getMerkById_returns_correct_data_structure()
    {
        // Arrange
        $merk = Merk::factory()->create([
            MerkColumns::MERK => 'Honda',
            MerkColumns::IS_ACTIVE => true
        ]);

        // Act
        $merkInstance = new Merk();
        $result = $merkInstance->getMerkById($merk->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertNotNull($result->id);
        $this->assertNotNull($result->{MerkColumns::MERK});
        $this->assertNotNull($result->{MerkColumns::IS_ACTIVE});
        $this->assertNotNull($result->created_at);
        $this->assertNotNull($result->updated_at);
    }

    /**
     * Test getMerkById handles zero ID
     * @test
     */
    public function test_getMerkById_handles_zero_id()
    {
        // Act
        $merkInstance = new Merk();
        $result = $merkInstance->getMerkById(0);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getMerkById handles negative ID
     * @test
     */
    public function test_getMerkById_handles_negative_id()
    {
        // Act
        $merkInstance = new Merk();
        $result = $merkInstance->getMerkById(-1);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getMerkById works with active merk
     * @test
     */
    public function test_getMerkById_works_with_active_merk()
    {
        // Arrange
        $activeMerk = Merk::factory()->active()->create([
            MerkColumns::MERK => 'Suzuki'
        ]);

        // Act
        $merkInstance = new Merk();
        $result = $merkInstance->getMerkById($activeMerk->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertTrue((bool)$result->{MerkColumns::IS_ACTIVE});
        $this->assertEquals('Suzuki', $result->{MerkColumns::MERK});
    }

    /**
     * Test getMerkById works with inactive merk
     * @test
     */
    public function test_getMerkById_works_with_inactive_merk()
    {
        // Arrange
        $inactiveMerk = Merk::factory()->inactive()->create([
            MerkColumns::MERK => 'Daihatsu'
        ]);

        // Act
        $merkInstance = new Merk();
        $result = $merkInstance->getMerkById($inactiveMerk->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertFalse((bool)$result->{MerkColumns::IS_ACTIVE});
        $this->assertEquals('Daihatsu', $result->{MerkColumns::MERK});
    }

    /**
     * Test getMerkById returns merk with all attributes
     * @test
     */
    public function test_getMerkById_returns_merk_with_all_attributes()
    {
        // Arrange
        $merk = Merk::factory()->create([
            MerkColumns::MERK => 'Yamaha',
            MerkColumns::IS_ACTIVE => true
        ]);

        // Act
        $merkInstance = new Merk();
        $result = $merkInstance->getMerkById($merk->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($merk->id, $result->id);
        $this->assertEquals($merk->{MerkColumns::MERK}, $result->{MerkColumns::MERK});
        $this->assertEquals($merk->{MerkColumns::IS_ACTIVE}, $result->{MerkColumns::IS_ACTIVE});
        $this->assertEquals($merk->created_at->timestamp, $result->created_at->timestamp);
        $this->assertEquals($merk->updated_at->timestamp, $result->updated_at->timestamp);
    }

    /**
     * Test getMerkById with string ID (type coercion)
     * @test
     */
    public function test_getMerkById_handles_string_id()
    {
        // Arrange
        $merk = Merk::factory()->create([
            MerkColumns::MERK => 'Mitsubishi'
        ]);

        // Act
        $merkInstance = new Merk();
        $result = $merkInstance->getMerkById((string)$merk->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($merk->id, $result->id);
        $this->assertEquals('Mitsubishi', $result->{MerkColumns::MERK});
    }

    /**
     * Test getMerkById returns specific merk when multiple exist
     * @test
     */
    public function test_getMerkById_returns_specific_merk_when_multiple_exist()
    {
        // Arrange
        $merk1 = Merk::factory()->create([MerkColumns::MERK => 'Kawasaki']);
        $merk2 = Merk::factory()->create([MerkColumns::MERK => 'Mazda']);
        $merk3 = Merk::factory()->create([MerkColumns::MERK => 'Nissan']);

        // Act
        $merkInstance = new Merk();
        $result = $merkInstance->getMerkById($merk2->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($merk2->id, $result->id);
        $this->assertEquals('Mazda', $result->{MerkColumns::MERK});
        $this->assertNotEquals($merk1->id, $result->id);
        $this->assertNotEquals($merk3->id, $result->id);
    }

    /**
     * Test getMerkById with freshly created merk
     * @test
     */
    public function test_getMerkById_works_with_freshly_created_merk()
    {
        // Arrange
        $newMerk = Merk::factory()->create([
            MerkColumns::MERK => 'Isuzu',
            MerkColumns::IS_ACTIVE => true
        ]);

        // Act - immediately fetch
        $merkInstance = new Merk();
        $result = $merkInstance->getMerkById($newMerk->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals('Isuzu', $result->{MerkColumns::MERK});
        $this->assertTrue((bool)$result->{MerkColumns::IS_ACTIVE});
    }

    /**
     * Test getMerkById returns instance with appended attributes
     * @test
     */
    public function test_getMerkById_returns_instance_with_appended_attributes()
    {
        // Arrange
        $merk = Merk::factory()->active()->create([
            MerkColumns::MERK => 'Lexus'
        ]);

        // Act
        $merkInstance = new Merk();
        $result = $merkInstance->getMerkById($merk->id);

        // Assert
        $this->assertNotNull($result);
        
        // Check appended attributes (dari getStatusLabelAttribute & getDisplayNameAttribute)
        $this->assertEquals('Aktif', $result->status_label);
        $this->assertStringContainsString('Lexus', $result->display_name);
        $this->assertStringContainsString('✅', $result->display_name);
    }

    // ========================================================================
    // TEST UNTUK FUNGSI countMerek() 
    // ========================================================================

    /**
     * Test countMerek returns zero when table is empty
     * @test
     */
    public function test_countMerek_returns_zero_when_no_merk_exists()
    {
        // Act
        $count = Merk::countMerek();

        // Assert
        $this->assertEquals(0, $count);
        $this->assertIsInt($count);
    }

    /**
     * Test countMerek returns correct count with single record
     * @test
     */
    public function test_countMerek_returns_one_when_single_merk_exists()
    {
        // Arrange
        Merk::factory()->create([
            MerkColumns::MERK => 'Honda'
        ]);

        // Act
        $count = Merk::countMerek();

        // Assert
        $this->assertEquals(1, $count);
    }

    /**
     * Test countMerek returns correct count with multiple records
     * @test
     */
    public function test_countMerek_returns_correct_count_with_multiple_merk()
    {
        // Arrange
        $expectedCount = 5;
        Merk::factory()->count($expectedCount)->create();

        // Act
        $count = Merk::countMerek();

        // Assert
        $this->assertEquals($expectedCount, $count);
    }

    /**
     * Test countMerek includes both active and inactive merk
     * @test
     */
    public function test_countMerek_counts_both_active_and_inactive_merk()
    {
        // Arrange
        Merk::factory()->count(3)->active()->create();
        Merk::factory()->count(2)->inactive()->create();

        // Act
        $count = Merk::countMerek();

        // Assert
        $this->assertEquals(5, $count);
    }

    /**
     * Test countMerek updates correctly after deletion
     * @test
     */
    public function test_countMerek_updates_count_after_deletion()
    {
        // Arrange
        Merk::factory()->count(5)->create();
        $merkToDelete = Merk::first();

        // Act
        $countBefore = Merk::countMerek();
        $merkToDelete->delete();
        $countAfter = Merk::countMerek();

        // Assert
        $this->assertEquals(5, $countBefore);
        $this->assertEquals(4, $countAfter);
    }

    /**
     * Test countMerek updates correctly after creation
     * @test
     */
    public function test_countMerek_updates_count_after_creation()
    {
        // Arrange
        Merk::factory()->count(3)->create();

        // Act
        $countBefore = Merk::countMerek();
        Merk::factory()->create([
            MerkColumns::MERK => 'Yamaha'
        ]);
        $countAfter = Merk::countMerek();

        // Assert
        $this->assertEquals(3, $countBefore);
        $this->assertEquals(4, $countAfter);
    }

    /**
     * Test countMerek with large dataset
     * @test
     */
    public function test_countMerek_handles_large_dataset()
    {
        // Arrange
        $largeCount = 100;
        Merk::factory()->count($largeCount)->create();

        // Act
        $count = Merk::countMerek();

        // Assert
        $this->assertEquals($largeCount, $count);
    }

    /**
     * Test countMerek is equivalent to count() method
     * @test
     */
    public function test_countMerek_returns_same_result_as_eloquent_count()
    {
        // Arrange
        Merk::factory()->count(10)->create();

        // Act
        $countMerekResult = Merk::countMerek();
        $eloquentCountResult = Merk::count();

        // Assert
        $this->assertEquals($eloquentCountResult, $countMerekResult);
    }

    /**
     * Test countMerek performance with medium dataset
     * @test
     */
    public function test_countMerek_executes_efficiently()
    {
        // Arrange
        Merk::factory()->count(50)->create();

        // Act
        $startTime = microtime(true);
        $count = Merk::countMerek();
        $executionTime = microtime(true) - $startTime;

        // Assert
        $this->assertEquals(50, $count);
        $this->assertLessThan(0.1, $executionTime, 'Query should execute in less than 100ms');
    }

    /**
     * Test countMerek matches getStatistics total
     * @test
     */
    public function test_countMerek_matches_statistics_total()
    {
        // Arrange
        Merk::factory()->count(7)->create();

        // Act
        $countResult = Merk::countMerek();
        $statistics = Merk::getStatistics();

        // Assert
        $this->assertEquals($countResult, $statistics['total_merk']);
    }

    /**
     * Test countMerek is not affected by ID gaps
     * @test
     */
    public function test_countMerek_is_not_affected_by_id_gaps()
    {
        // Arrange - Create merks and delete some to create gaps in IDs
        $merk1 = Merk::factory()->create();
        $merk2 = Merk::factory()->create();
        $merk3 = Merk::factory()->create();
        
        $merk2->delete();

        // Act
        $count = Merk::countMerek();

        // Assert
        $this->assertEquals(2, $count); // Should count actual records, not ID numbers
    }

    /**
     * Test countMerek returns correct integer type
     * @test
     */
    public function test_countMerek_returns_integer_type()
    {
        // Arrange
        Merk::factory()->count(5)->create();

        // Act
        $count = Merk::countMerek();

        // Assert
        $this->assertIsInt($count);
        $this->assertNotNull($count);
    }

    /**
     * Test countMerek works with various merk names
     * @test
     */
    public function test_countMerek_works_with_various_merk_names()
    {
        // Arrange
        Merk::factory()->create([MerkColumns::MERK => 'Toyota']);
        Merk::factory()->create([MerkColumns::MERK => 'Honda']);
        Merk::factory()->create([MerkColumns::MERK => 'Suzuki']);
        Merk::factory()->create([MerkColumns::MERK => 'Yamaha']);

        // Act
        $count = Merk::countMerek();

        // Assert
        $this->assertEquals(4, $count);
    }

    /**
     * Test countMerek remains accurate after multiple operations
     * @test
     */
    public function test_countMerek_accurate_after_multiple_operations()
    {
        // Arrange & Act - Test step by step
        Merk::factory()->count(3)->create();
        $this->assertEquals(3, Merk::countMerek());

        Merk::factory()->count(2)->create();
        $this->assertEquals(5, Merk::countMerek());

        Merk::first()->delete();
        $this->assertEquals(4, Merk::countMerek());

        Merk::factory()->create();
        
        // Final Assert
        $this->assertEquals(5, Merk::countMerek());
    }
}