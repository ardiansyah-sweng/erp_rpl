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

    // ========================================================================
    // TEST UNTUK DELETE OPERATIONS (Laravel Eloquent delete() on Merk model)
    // These tests cover Laravel's built-in Eloquent delete() method on Merk instances,
    // not a custom deleteMerkByID() method.
    // Based on PRD Test Cases TC-MK-19 to TC-MK-23
    // ========================================================================

    /**
     * Test deleting a merk successfully
     * Corresponds to TC-MK-19 from PRD (Happy Path)
     * @test
     */
    public function test_it_can_delete_merk_successfully()
    {
        // Arrange - Create a merk record
        $merk = Merk::factory()->create([
            MerkColumns::MERK => 'Test Brand',
            MerkColumns::IS_ACTIVE => true
        ]);
        $merkId = $merk->id;
        $initialCount = Merk::count();

        // Act - Perform delete operation
        $result = $merk->delete();

        // Assert - Verify deletion was successful
        $this->assertTrue($result, 'Delete operation should return true');
        $this->assertDatabaseMissing(config('db_tables.merk'), [
            'id' => $merkId
        ]);
        $this->assertNull(Merk::find($merkId), 'Merk should not be found after deletion');
        $this->assertEquals($initialCount - 1, Merk::count(), 'Total merk count should decrease by 1');
    }

    /**
     * Test deleting an active merk successfully
     * (Happy Path)
     * @test
     */
    public function test_it_can_delete_active_merk()
    {
        // Arrange - Create an active merk
        $merk = Merk::factory()->active()->create([
            MerkColumns::MERK => 'Active Brand'
        ]);
        $merkId = $merk->id;

        // Act - Delete the active merk
        $result = $merk->delete();

        // Assert - Verify deletion
        $this->assertTrue($result);
        $this->assertDatabaseMissing(config('db_tables.merk'), [
            'id' => $merkId,
            MerkColumns::IS_ACTIVE => true
        ]);
        $this->assertNull(Merk::find($merkId));
    }

    /**
     * Test deleting an inactive merk successfully
     * (Happy Path)
     * @test
     */
    public function test_it_can_delete_inactive_merk()
    {
        // Arrange - Create an inactive merk
        $merk = Merk::factory()->inactive()->create([
            MerkColumns::MERK => 'Inactive Brand'
        ]);
        $merkId = $merk->id;

        // Act - Delete the inactive merk
        $result = $merk->delete();

        // Assert - Verify deletion
        $this->assertTrue($result);
        $this->assertDatabaseMissing(config('db_tables.merk'), [
            'id' => $merkId,
            MerkColumns::IS_ACTIVE => false
        ]);
    }

    /**
     * Test database count decreases correctly after merk deletion
     * (Happy Path)
     * @test
     */
    public function test_count_decreases_correctly_after_merk_deletion()
    {
        // Arrange - Create multiple merks
        Merk::factory()->count(5)->create();
        $countBeforeDelete = Merk::count();
        $this->assertEquals(5, $countBeforeDelete);

        // Act - Delete one merk
        $merkToDelete = Merk::first();
        $merkToDelete->delete();

        // Assert - Verify count decreased
        $countAfterDelete = Merk::count();
        $this->assertEquals(4, $countAfterDelete);
        $this->assertEquals($countBeforeDelete - 1, $countAfterDelete);
    }

    /**
     * Test deleting multiple merks in sequence
     * (Happy Path)
     * @test
     */
    public function test_it_can_delete_multiple_merks_in_sequence()
    {
        // Arrange - Create 3 merks
        $merk1 = Merk::factory()->create([MerkColumns::MERK => 'Brand 1']);
        $merk2 = Merk::factory()->create([MerkColumns::MERK => 'Brand 2']);
        $merk3 = Merk::factory()->create([MerkColumns::MERK => 'Brand 3']);
        
        $initialCount = Merk::count();
        $this->assertEquals(3, $initialCount);

        // Act - Delete merks one by one
        $result1 = $merk1->delete();
        $result2 = $merk2->delete();
        $result3 = $merk3->delete();

        // Assert - Verify all deletions were successful
        $this->assertTrue($result1);
        $this->assertTrue($result2);
        $this->assertTrue($result3);
        $this->assertEquals(0, Merk::count());
        $this->assertDatabaseMissing(config('db_tables.merk'), ['id' => $merk1->id]);
        $this->assertDatabaseMissing(config('db_tables.merk'), ['id' => $merk2->id]);
        $this->assertDatabaseMissing(config('db_tables.merk'), ['id' => $merk3->id]);
    }

    /**
     * Test deleting non-existent merk returns null
     * Corresponds to TC-MK-21 from PRD (Negative Case)
     * @test
     */
    public function test_delete_non_existent_merk_returns_null()
    {
        // Arrange - Use an ID that doesn't exist
        $nonExistentId = 99999;

        // Act - Try to find and delete
        $merk = Merk::find($nonExistentId);

        // Assert - Verify merk was not found
        $this->assertNull($merk, 'Finding non-existent merk should return null');
    }

    /**
     * Test attempting to delete already deleted merk
     * (Edge Case)
     * @test
     */
    public function test_cannot_delete_already_deleted_merk()
    {
        // Arrange - Create and delete a merk
        $merk = Merk::factory()->create([
            MerkColumns::MERK => 'To Be Deleted'
        ]);
        $merkId = $merk->id;
        $merk->delete();

        // Act - Try to find the deleted merk
        $deletedMerk = Merk::find($merkId);

        // Assert - Verify it cannot be found
        $this->assertNull($deletedMerk, 'Already deleted merk should not be found');
    }

    /**
     * Test deleting merk with invalid ID type (string)
     * (Edge Case)
     * @test
     */
    public function test_delete_with_invalid_string_id_returns_null()
    {
        // Arrange - Create a merk
        Merk::factory()->create();

        // Act - Try to find with string ID
        $result = Merk::find('invalid_string');

        // Assert - Should return null
        $this->assertNull($result, 'Finding merk with invalid string ID should return null');
    }

    /**
     * Test deleting merk with negative ID returns null
     * (Edge Case)
     * @test
     */
    public function test_delete_with_negative_id_returns_null()
    {
        // Arrange - Create a merk
        Merk::factory()->create();

        // Act - Try to find with negative ID
        $result = Merk::find(-1);

        // Assert - Should return null
        $this->assertNull($result, 'Finding merk with negative ID should return null');
    }

    /**
     * Test verify other merks remain intact after deletion
     * (Edge Case - Data Integrity)
     * @test
     */
    public function test_other_merks_remain_intact_after_deletion()
    {
        // Arrange - Create 3 merks
        $merk1 = Merk::factory()->create([MerkColumns::MERK => 'Brand A']);
        $merk2 = Merk::factory()->create([MerkColumns::MERK => 'Brand B']);
        $merk3 = Merk::factory()->create([MerkColumns::MERK => 'Brand C']);

        // Act - Delete only the middle one
        $merk2->delete();

        // Assert - Verify other merks still exist
        $this->assertNotNull(Merk::find($merk1->id), 'Merk 1 should still exist');
        $this->assertNull(Merk::find($merk2->id), 'Merk 2 should be deleted');
        $this->assertNotNull(Merk::find($merk3->id), 'Merk 3 should still exist');
        
        // Verify the remaining merks have correct data
        $remainingMerk1 = Merk::find($merk1->id);
        $remainingMerk3 = Merk::find($merk3->id);
        
        $this->assertEquals('Brand A', $remainingMerk1->{MerkColumns::MERK});
        $this->assertEquals('Brand C', $remainingMerk3->{MerkColumns::MERK});
    }
}