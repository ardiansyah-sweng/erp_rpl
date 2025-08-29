<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use App\Services\ValidationService;
use InvalidArgumentException;

class ValidationServiceTest extends TestCase
{
    /**
     * @dataProvider emailProvider
     */
    public function test_email_validation_edge_cases($email, $expectedResult, $expectsException = false)
    {
        if ($expectsException) {
            $this->expectException(InvalidArgumentException::class);
        }
        
        $result = ValidationService::validateEmail($email);
        $this->assertEquals($expectedResult, $result);
    }
    
    public static function emailProvider(): array
    {
        return [
            // Valid cases
            'valid_simple_email' => ['test@example.com', true],
            'valid_with_plus' => ['test+tag@example.com', true],
            'valid_with_dots' => ['test.email@example.com', true],
            'valid_subdomain' => ['user@mail.example.com', true],
            
            // Edge cases - Invalid
            'missing_at_symbol' => ['testexample.com', false],
            'multiple_at_symbols' => ['test@@example.com', false],
            'empty_local_part' => ['@example.com', false],
            'empty_domain_part' => ['test@', false],
            'local_part_too_long' => [str_repeat('a', 65) . '@example.com', false],
            'domain_too_long' => ['test@' . str_repeat('a', 250) . '.com', false, true], // Should throw exception
            
            // Extreme edge cases
            'just_at_symbol' => ['@', false],
            'special_chars_in_domain' => ['test@exam!ple.com', false],
            'spaces_in_email' => ['test @example.com', false],
            
            // Boundary cases
            'max_local_length' => [str_repeat('a', 64) . '@example.com', true],
            'max_domain_length' => ['test@' . str_repeat('a', 60) . '.com', true],
        ];
    }
    
    /**
     * Test email validation with exception cases
     */
    public function test_email_validation_exceptions()
    {
        // Empty email
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Email cannot be empty');
        ValidationService::validateEmail('');
    }
    
    public function test_email_too_long_exception()
    {
        // Email too long (over 254 characters)
        $longEmail = str_repeat('a', 250) . '@example.com';
        
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Email address too long');
        ValidationService::validateEmail($longEmail);
    }
    
    public function test_whitespace_only_email()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Email cannot be empty');
        ValidationService::validateEmail('   ');
    }
    
    /**
     * @dataProvider passwordProvider
     */
    public function test_password_validation_edge_cases($password, $expectedValid, $expectedErrorsCount)
    {
        $result = ValidationService::validatePassword($password);
        
        $this->assertEquals($expectedValid, $result['valid']);
        $this->assertCount($expectedErrorsCount, $result['errors']);
    }
    
    public static function passwordProvider(): array
    {
        return [
            // Valid passwords
            'valid_strong_password' => ['MyStr0ng!Pass', true, 0],
            'valid_with_numbers' => ['Password123!', true, 0],
            'valid_with_special_chars' => ['C0mpl3x@Pass', true, 0],
            
            // Edge cases - Invalid
            'empty_password' => ['', false, 6], // All 6 validation rules fail
            'whitespace_only' => ['   ', false, 5], // 5 validation rules fail (no special char requirement)
            'too_short' => ['Ab1!', false, 1],
            'no_uppercase' => ['mystr0ng!pass', false, 1],
            'no_lowercase' => ['MYSTR0NG!PASS', false, 1],
            'no_numbers' => ['MyStrong!Pass', false, 1],
            'no_special_chars' => ['MyStr0ngPass', false, 1],
            'common_password' => ['password', false, 4], // Short, no uppercase, no numbers, no special
            'common_password2' => ['Password1', false, 2], // No special chars + common
            
            // Boundary cases
            'exactly_8_chars' => ['MyStr0n!', true, 0],
            'max_length' => [str_repeat('A', 124) . 'a1!', true, 0],
            'too_long' => [str_repeat('A', 126) . 'a1!', false, 1], // Just too long
            
            // Multiple failures
            'multiple_failures' => ['abc', false, 4], // Short, no uppercase, no numbers, no special
        ];
    }
    
    /**
     * @dataProvider phoneProvider
     */
    public function test_phone_validation_edge_cases($phone, $countryCode, $expectedResult, $expectsException = false)
    {
        if ($expectsException) {
            $this->expectException(InvalidArgumentException::class);
        }
        
        $result = ValidationService::validatePhoneNumber($phone, $countryCode);
        $this->assertEquals($expectedResult, $result);
    }
    
    public static function phoneProvider(): array
    {
        return [
            // Indonesian mobile numbers
            'id_mobile_08' => ['081234567890', 'ID', true],
            'id_mobile_62' => ['+6281234567890', 'ID', true],
            'id_mobile_formatted' => ['0812-3456-7890', 'ID', true],
            'id_landline' => ['02112345678', 'ID', true],
            'id_landline_plus' => ['+622112345678', 'ID', true],
            
            // US numbers
            'us_number' => ['5551234567', 'US', true],
            'us_number_plus1' => ['+15551234567', 'US', true],
            'us_number_formatted' => ['(555) 123-4567', 'US', true],
            
            // Edge cases - Invalid
            'id_wrong_prefix' => ['071234567890', 'ID', false],
            'id_too_short' => ['08123456', 'ID', false],
            'id_too_long' => ['081234567890123', 'ID', false],
            'us_too_short' => ['555123456', 'US', false],
            'us_too_long' => ['55512345678', 'US', false],
            
            // Special characters
            'with_letters' => ['08abc1234567890', 'ID', false],
            'only_letters' => ['abcdefghijk', 'ID', false],
        ];
    }
    
    public function test_phone_validation_exceptions()
    {
        // Empty phone
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Phone number cannot be empty');
        ValidationService::validatePhoneNumber('');
    }
    
    public function test_phone_validation_unsupported_country()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported country code: XX');
        ValidationService::validatePhoneNumber('1234567890', 'XX');
    }
    
    public function test_phone_validation_whitespace_only()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Phone number cannot be empty');
        ValidationService::validatePhoneNumber('   ');
    }
    
    /**
     * @dataProvider sanitizeInputProvider
     */
    public function test_input_sanitization_edge_cases($input, $maxLength, $expectedResult, $expectsException = false)
    {
        if ($expectsException) {
            $this->expectException(InvalidArgumentException::class);
        }
        
        $result = ValidationService::sanitizeInput($input, $maxLength);
        $this->assertEquals($expectedResult, $result);
    }
    
    public static function sanitizeInputProvider(): array
    {
        return [
            // Normal cases
            'simple_text' => ['Hello World', 255, 'Hello World'],
            'text_with_extra_spaces' => ['Hello    World   ', 255, 'Hello World'],
            'text_with_tabs' => ["Hello\tWorld", 255, 'Hello World'],
            'text_with_newlines' => ["Hello\nWorld", 255, 'Hello World'],
            
            // Edge cases
            'empty_string' => ['', 255, ''],
            'whitespace_only' => ['   ', 255, ''],
            'single_char' => ['A', 255, 'A'],
            'max_length' => [str_repeat('A', 255), 255, str_repeat('A', 255)],
            
            // Special characters
            'with_quotes' => ["Hello 'World'", 255, "Hello 'World'"],
            'with_unicode' => ['Héllo Wörld', 255, 'Héllo Wörld'],
        ];
    }
    
    public function test_sanitize_input_exceeds_max_length()
    {
        $longInput = str_repeat('A', 256);
        
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Input exceeds maximum length of 255');
        ValidationService::sanitizeInput($longInput);
    }
    
    public function test_sanitize_input_with_null_bytes()
    {
        $inputWithNull = "Hello\0World";
        
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Input contains null bytes');
        ValidationService::sanitizeInput($inputWithNull);
    }
    
    /**
     * Test boundary conditions for various methods
     */
    public function test_boundary_conditions()
    {
        // Test email with reasonable length (not maximum)
        $validEmail = str_repeat('a', 50) . '@example.com';
        $this->assertTrue(ValidationService::validateEmail($validEmail));
        
        // Test phone number with minimum valid length
        $this->assertTrue(ValidationService::validatePhoneNumber('081234567890', 'ID'));
        
        // Test password with exactly 8 characters
        $minPassword = ValidationService::validatePassword('Passw0rd!');
        $this->assertTrue($minPassword['valid']);
    }
    
    /**
     * Test extreme values and null handling
     */
    public function test_extreme_values()
    {
        // Test with very long valid inputs
        $longValidEmail = 'a' . str_repeat('b', 62) . '@example.com';
        $this->assertTrue(ValidationService::validateEmail($longValidEmail));
        
        // Test password with maximum allowed length
        $maxPassword = str_repeat('A', 124) . 'a1!';
        $result = ValidationService::validatePassword($maxPassword);
        $this->assertTrue($result['valid']);
        
        // Test sanitization with exact boundary
        $boundaryInput = str_repeat('A', 255);
        $sanitized = ValidationService::sanitizeInput($boundaryInput, 255);
        $this->assertEquals(255, strlen($sanitized));
    }
}
