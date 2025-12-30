<?php

namespace Tests\Unit\Helpers;

use App\Helpers\CurrencyHelper;
use PHPUnit\Framework\TestCase; // Note: Not extending Laravel's TestCase
use InvalidArgumentException;

/**
 * Pure Unit Tests untuk CurrencyHelper
 * 
 * Characteristics:
 * - No database dependencies
 * - No Laravel framework dependencies  
 * - Fast execution (< 1ms per test)
 * - Isolated and repeatable
 */
class CurrencyHelperTest extends TestCase
{
    /**
     * Test: Format IDR currency correctly
     * @test
     */
    public function it_formats_idr_currency_correctly(): void
    {
        // Arrange
        $amount = 1500000;
        
        // Act
        $result = CurrencyHelper::format($amount, 'IDR');
        
        // Assert
        $this->assertEquals('Rp 1.500.000', $result);
    }
    
    /**
     * Test: Format USD currency correctly
     * @test
     */
    public function it_formats_usd_currency_correctly(): void
    {
        // Arrange
        $amount = 1234.56;
        
        // Act
        $result = CurrencyHelper::format($amount, 'USD');
        
        // Assert
        $this->assertEquals('$1,234.56', $result);
    }
    
    /**
     * Test: Default currency is IDR
     * @test
     */
    public function it_uses_idr_as_default_currency(): void
    {
        // Act
        $result = CurrencyHelper::format(100000);
        
        // Assert
        $this->assertEquals('Rp 100.000', $result);
    }
    
    /**
     * Test: Exception for negative amount
     * @test
     */
    public function it_throws_exception_for_negative_amount(): void
    {
        // Arrange & Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Amount cannot be negative');
        
        // Act
        CurrencyHelper::format(-100);
    }
    
    /**
     * Test: Exception for unsupported currency
     * @test
     */
    public function it_throws_exception_for_unsupported_currency(): void
    {
        // Arrange & Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported currency: EUR');
        
        // Act
        CurrencyHelper::format(100, 'EUR');
    }
    
    /**
     * Test: Parse currency string to amount
     * @test
     * @dataProvider currencyParsingProvider
     */
    public function it_parses_currency_string_to_amount($input, $expected): void
    {
        // Act
        $result = CurrencyHelper::parse($input);
        
        // Assert
        $this->assertEquals($expected, $result);
    }
    
    public static function currencyParsingProvider(): array
    {
        return [
            'idr_format' => ['Rp 1.500.000', 1500000],
            'usd_format' => ['$1,234.56', 1234.56],
            'numbers_only' => ['123456', 123456],
            'with_spaces' => ['Rp 100 000', 100000]
        ];
    }
    
    /**
     * Test: Calculate percentage correctly
     * @test
     * @dataProvider percentageProvider
     */
    public function it_calculates_percentage_correctly($amount, $percentage, $expected): void
    {
        // Act
        $result = CurrencyHelper::calculatePercentage($amount, $percentage);
        
        // Assert
        $this->assertEquals($expected, $result);
    }
    
    public static function percentageProvider(): array
    {
        return [
            'ten_percent' => [1000, 10, 100.0],
            'fifty_percent' => [200, 50, 100.0],
            'zero_percent' => [1000, 0, 0.0],
            'hundred_percent' => [500, 100, 500.0],
            'decimal_percentage' => [1000, 12.5, 125.0]
        ];
    }
    
    /**
     * Test: Exception for invalid percentage
     * @test
     * @dataProvider invalidPercentageProvider
     */
    public function it_throws_exception_for_invalid_percentage($invalidPercentage): void
    {
        // Arrange & Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Percentage must be between 0 and 100');
        
        // Act
        CurrencyHelper::calculatePercentage(1000, $invalidPercentage);
    }
    
    public static function invalidPercentageProvider(): array
    {
        return [
            'negative' => [-1],
            'over_hundred' => [101],
            'way_over' => [999]
        ];
    }
}