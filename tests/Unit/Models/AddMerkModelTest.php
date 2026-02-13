<?php

namespace Tests\Unit\Models;

use App\Models\Merk;
use App\Constants\MerkColumns;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddMerkModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['db_tables.merk' => 'merks']);
    }

    /**
     * Test: Can add merk with specific active status
     */
    public function test_can_add_merk_with_specific_active_status()
    {
        // Arrange
        $namaMerk = 'Toyota Kijang Innova';
        $isActive = 0;

        // Act
        $merk = Merk::addMerk($namaMerk, $isActive);

        // Assert
        $this->assertInstanceOf(Merk::class, $merk);
        $this->assertDatabaseHas('merks', [
            MerkColumns::MERK => $namaMerk,
            MerkColumns::IS_ACTIVE => $isActive,
        ]);
    }

    /**
     * Test: Uses default active value when not provided
     */
    public function test_uses_default_active_value_when_not_provided()
    {
        // Arrange
        $namaMerk = 'Honda Civic Turbo';

        // Act
        $merk = Merk::addMerk($namaMerk);

        // Assert
        $this->assertEquals(1, $merk->{MerkColumns::IS_ACTIVE});
        $this->assertDatabaseHas('merks', [
            MerkColumns::MERK => $namaMerk,
            MerkColumns::IS_ACTIVE => 1,
        ]);
    }

}
