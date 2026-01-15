<?php

namespace Tests\Unit\Constants;

use Tests\TestCase;
use App\Constants\BranchColumns;
use PHPUnit\Framework\Attributes\Test;

class BranchColumnsTest extends TestCase
{
    #[Test]
    public function it_has_correct_column_constants()
    {
        // Assert all constants are defined with correct values
        $this->assertEquals('id', BranchColumns::ID);
        $this->assertEquals('branch_name', BranchColumns::NAME);
        $this->assertEquals('branch_address', BranchColumns::ADDRESS);
        $this->assertEquals('branch_telephone', BranchColumns::PHONE);
        $this->assertEquals('is_active', BranchColumns::IS_ACTIVE);
        $this->assertEquals('created_at', BranchColumns::CREATED_AT);
        $this->assertEquals('updated_at', BranchColumns::UPDATED_AT);
    }

    #[Test]
    public function it_returns_correct_fillable_columns()
    {
        // Arrange
        $expectedFillable = [
            'branch_name',
            'branch_address',
            'branch_telephone',
            'is_active'
        ];

        // Act
        $fillable = BranchColumns::getFillable();

        // Assert
        $this->assertEquals($expectedFillable, $fillable);
        $this->assertCount(4, $fillable);
        
        // Assert excludes non-fillable columns
        $this->assertNotContains(BranchColumns::ID, $fillable);
        $this->assertNotContains(BranchColumns::CREATED_AT, $fillable);
        $this->assertNotContains(BranchColumns::UPDATED_AT, $fillable);
    }

    #[Test]
    public function it_returns_all_columns()
    {
        // Arrange
        $expectedAll = [
            'id',
            'branch_name',
            'branch_address',
            'branch_telephone',
            'is_active',
            'created_at',
            'updated_at'
        ];

        // Act
        $allColumns = BranchColumns::getAll();

        // Assert
        $this->assertEquals($expectedAll, $allColumns);
        $this->assertCount(7, $allColumns);
        
        // Assert includes all defined constants
        $this->assertContains(BranchColumns::ID, $allColumns);
        $this->assertContains(BranchColumns::NAME, $allColumns);
        $this->assertContains(BranchColumns::ADDRESS, $allColumns);
        $this->assertContains(BranchColumns::PHONE, $allColumns);
        $this->assertContains(BranchColumns::IS_ACTIVE, $allColumns);
        $this->assertContains(BranchColumns::CREATED_AT, $allColumns);
        $this->assertContains(BranchColumns::UPDATED_AT, $allColumns);
    }

    #[Test]
    public function it_fillable_is_subset_of_all_columns()
    {
        // Arrange
        $fillable = BranchColumns::getFillable();
        $allColumns = BranchColumns::getAll();

        // Act & Assert
        foreach ($fillable as $column) {
            $this->assertContains($column, $allColumns, "Fillable column '{$column}' should be in all columns");
        }
    }

    #[Test]
    public function it_has_unique_column_values()
    {
        // Arrange
        $allColumns = BranchColumns::getAll();

        // Act
        $uniqueColumns = array_unique($allColumns);

        // Assert
        $this->assertEquals($allColumns, $uniqueColumns, 'All column values should be unique');
        $this->assertCount(count($allColumns), $uniqueColumns);
    }

    #[Test]
    public function it_constants_are_strings()
    {
        // Assert all constants are strings
        $this->assertIsString(BranchColumns::ID);
        $this->assertIsString(BranchColumns::NAME);
        $this->assertIsString(BranchColumns::ADDRESS);
        $this->assertIsString(BranchColumns::PHONE);
        $this->assertIsString(BranchColumns::IS_ACTIVE);
        $this->assertIsString(BranchColumns::CREATED_AT);
        $this->assertIsString(BranchColumns::UPDATED_AT);
    }

    #[Test]
    public function it_constants_are_not_empty()
    {
        // Assert all constants are not empty
        $this->assertNotEmpty(BranchColumns::ID);
        $this->assertNotEmpty(BranchColumns::NAME);
        $this->assertNotEmpty(BranchColumns::ADDRESS);
        $this->assertNotEmpty(BranchColumns::PHONE);
        $this->assertNotEmpty(BranchColumns::IS_ACTIVE);
        $this->assertNotEmpty(BranchColumns::CREATED_AT);
        $this->assertNotEmpty(BranchColumns::UPDATED_AT);
    }

    #[Test]
    public function it_fillable_contains_expected_business_fields()
    {
        // Arrange
        $fillable = BranchColumns::getFillable();

        // Assert business-critical fields are fillable
        $this->assertContains(BranchColumns::NAME, $fillable, 'Branch name should be fillable');
        $this->assertContains(BranchColumns::ADDRESS, $fillable, 'Branch address should be fillable');
        $this->assertContains(BranchColumns::PHONE, $fillable, 'Branch phone should be fillable');
        $this->assertContains(BranchColumns::IS_ACTIVE, $fillable, 'Branch status should be fillable');
    }

    #[Test]
    public function it_excludes_system_fields_from_fillable()
    {
        // Arrange
        $fillable = BranchColumns::getFillable();

        // Assert system fields are not fillable
        $this->assertNotContains(BranchColumns::ID, $fillable, 'ID should not be fillable');
        $this->assertNotContains(BranchColumns::CREATED_AT, $fillable, 'Created at should not be fillable');
        $this->assertNotContains(BranchColumns::UPDATED_AT, $fillable, 'Updated at should not be fillable');
    }

    #[Test]
    public function it_methods_return_arrays()
    {
        // Act & Assert
        $this->assertIsArray(BranchColumns::getFillable());
        $this->assertIsArray(BranchColumns::getAll());
    }

    #[Test]
    public function it_methods_are_static()
    {
        // Assert methods can be called statically without instantiation
        $this->assertTrue(method_exists(BranchColumns::class, 'getFillable'));
        $this->assertTrue(method_exists(BranchColumns::class, 'getAll'));
        
        // Verify they are static methods
        $reflection = new \ReflectionClass(BranchColumns::class);
        $this->assertTrue($reflection->getMethod('getFillable')->isStatic());
        $this->assertTrue($reflection->getMethod('getAll')->isStatic());
    }

    #[Test]
    public function it_follows_laravel_naming_conventions()
    {
        // Assert column names follow Laravel conventions
        $this->assertEquals('created_at', BranchColumns::CREATED_AT);
        $this->assertEquals('updated_at', BranchColumns::UPDATED_AT);
        $this->assertEquals('is_active', BranchColumns::IS_ACTIVE);
        
        // Assert uses snake_case for database columns
        $this->assertMatchesRegularExpression('/^[a-z_]+$/', BranchColumns::NAME);
        $this->assertMatchesRegularExpression('/^[a-z_]+$/', BranchColumns::ADDRESS);
        $this->assertMatchesRegularExpression('/^[a-z_]+$/', BranchColumns::PHONE);
    }
}