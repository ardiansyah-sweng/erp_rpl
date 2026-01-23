<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Category;
use App\Constants\CategoryColumns;

class CategoryTest extends TestCase
{
    /**
     * Test searchCategory method exists
     */
    public function test_search_category_method_exists()
    {
        // Assert
        $this->assertTrue(
            method_exists(Category::class, 'searchCategory'),
            'Method searchCategory should exist in Category model'
        );
    }

    /**
     * Test searchCategory accepts string parameter
     */
    public function test_search_category_accepts_string_parameter()
    {
        // Arrange
        $reflection = new \ReflectionMethod(Category::class, 'searchCategory');
        $parameters = $reflection->getParameters();

        // Assert
        $this->assertCount(1, $parameters, 'searchCategory should accept exactly 1 parameter');
        $this->assertEquals('keyword', $parameters[0]->getName(), 'Parameter should be named keyword');
    }

    /**
     * Test searchCategory returns correct type
     */
    public function test_search_category_return_type()
    {
        // Arrange
        $reflection = new \ReflectionMethod(Category::class, 'searchCategory');
        
        // Assert
        $this->assertTrue(
            $reflection->isStatic(),
            'searchCategory should be a static method'
        );
    }

    /**
     * Test searchCategory uses where clause
     */
    public function test_search_category_uses_where_clause()
    {
        // Arrange
        $method = new \ReflectionMethod(Category::class, 'searchCategory');
        $source = file_get_contents($method->getFileName());
        
        // Extract method body
        $start = $method->getStartLine() - 1;
        $end = $method->getEndLine();
        $length = $end - $start;
        $lines = array_slice(file($method->getFileName()), $start, $length);
        $methodBody = implode('', $lines);

        // Assert
        $this->assertStringContainsString('where', strtolower($methodBody), 
            'searchCategory should use where clause');
        $this->assertStringContainsString('LIKE', $methodBody, 
            'searchCategory should use LIKE operator');
    }

    /**
     * Test searchCategory uses with for eager loading
     */
    public function test_search_category_uses_eager_loading()
    {
        // Arrange
        $method = new \ReflectionMethod(Category::class, 'searchCategory');
        $source = file_get_contents($method->getFileName());
        
        // Extract method body
        $start = $method->getStartLine() - 1;
        $end = $method->getEndLine();
        $length = $end - $start;
        $lines = array_slice(file($method->getFileName()), $start, $length);
        $methodBody = implode('', $lines);

        // Assert
        $this->assertStringContainsString('with', strtolower($methodBody), 
            'searchCategory should use with() for eager loading');
        $this->assertStringContainsString('parent', strtolower($methodBody), 
            'searchCategory should eager load parent relationship');
    }

    /**
     * Test searchCategory returns collection
     */
    public function test_search_category_returns_collection_via_get()
    {
        // Arrange
        $method = new \ReflectionMethod(Category::class, 'searchCategory');
        $source = file_get_contents($method->getFileName());
        
        // Extract method body
        $start = $method->getStartLine() - 1;
        $end = $method->getEndLine();
        $length = $end - $start;
        $lines = array_slice(file($method->getFileName()), $start, $length);
        $methodBody = implode('', $lines);

        // Assert
        $this->assertStringContainsString('get()', $methodBody, 
            'searchCategory should use get() to return collection');
    }

    /**
     * Test searchCategory uses correct column constant
     */
    public function test_search_category_uses_category_column_constant()
    {
        // Arrange
        $method = new \ReflectionMethod(Category::class, 'searchCategory');
        $source = file_get_contents($method->getFileName());
        
        // Extract method body
        $start = $method->getStartLine() - 1;
        $end = $method->getEndLine();
        $length = $end - $start;
        $lines = array_slice(file($method->getFileName()), $start, $length);
        $methodBody = implode('', $lines);

        // Assert - should use either string 'category' or CategoryColumns constant
        $usesCorrectColumn = (
            stripos($methodBody, "'category'") !== false ||
            stripos($methodBody, '"category"') !== false ||
            stripos($methodBody, 'CategoryColumns::CATEGORY') !== false
        );

        $this->assertTrue($usesCorrectColumn, 
            'searchCategory should search on category column');
    }

    /**
     * Test searchCategory builds proper LIKE pattern
     */
    public function test_search_category_builds_like_pattern()
    {
        // Arrange
        $method = new \ReflectionMethod(Category::class, 'searchCategory');
        $source = file_get_contents($method->getFileName());
        
        // Extract method body
        $start = $method->getStartLine() - 1;
        $end = $method->getEndLine();
        $length = $end - $start;
        $lines = array_slice(file($method->getFileName()), $start, $length);
        $methodBody = implode('', $lines);

        // Assert - should wrap keyword with %
        $this->assertStringContainsString('%', $methodBody, 
            'searchCategory should use % wildcard for LIKE pattern');
    }

    /**
     * Test searchCategory is public method
     */
    public function test_search_category_is_public()
    {
        // Arrange
        $reflection = new \ReflectionMethod(Category::class, 'searchCategory');

        // Assert
        $this->assertTrue(
            $reflection->isPublic(),
            'searchCategory should be a public method'
        );
    }

    /**
     * Test Category model has searchCategory method signature
     */
    public function test_category_model_structure()
    {
        // Arrange
        $class = new \ReflectionClass(Category::class);
        $methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_STATIC);
        $methodNames = array_map(function($method) {
            return $method->getName();
        }, $methods);

        // Assert
        $this->assertContains('searchCategory', $methodNames,
            'Category model should have searchCategory as public static method');
    }
}