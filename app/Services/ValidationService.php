<?php

namespace App\Services;

use InvalidArgumentException;

class ValidationService
{
    /**
     * Validate email address with edge cases
     */
    public static function validateEmail(string $email): bool
    {
        if (empty(trim($email))) {
            throw new InvalidArgumentException('Email cannot be empty');
        }
        
        // Handle edge cases
        if (strlen($email) > 254) {
            throw new InvalidArgumentException('Email address too long');
        }
        
        if (substr_count($email, '@') !== 1) {
            return false;
        }
        
        [$local, $domain] = explode('@', $email);
        
        // Edge cases for local part
        if (empty($local) || strlen($local) > 64) {
            return false;
        }
        
        // Edge cases for domain part  
        if (empty($domain) || strlen($domain) > 253) {
            return false;
        }
        
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Validate password with complex requirements
     */
    public static function validatePassword(string $password): array
    {
        $errors = [];
        
        // Edge case: Empty or whitespace only
        if (empty(trim($password))) {
            $errors[] = 'Password cannot be empty';
        }
        
        // Length requirements with edge cases
        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters';
        }
        
        if (strlen($password) > 128) {
            $errors[] = 'Password cannot exceed 128 characters';
        }
        
        // Character requirements
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Password must contain at least one uppercase letter';
        }
        
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'Password must contain at least one lowercase letter';
        }
        
        if (!preg_match('/\d/', $password)) {
            $errors[] = 'Password must contain at least one number';
        }
        
        if (!preg_match('/[^A-Za-z\d]/', $password)) {
            $errors[] = 'Password must contain at least one special character';
        }
        
        // Edge case: Common passwords
        $commonPasswords = ['password', '12345678', 'qwerty123', 'Password1'];
        if (in_array($password, $commonPasswords)) {
            $errors[] = 'Password is too common';
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
    
    /**
     * Validate phone number with international formats
     */
    public static function validatePhoneNumber(string $phone, string $countryCode = 'ID'): bool
    {
        if (empty(trim($phone))) {
            throw new InvalidArgumentException('Phone number cannot be empty');
        }
        
        // Remove all non-digit characters except +
        $cleanPhone = preg_replace('/[^\d+]/', '', $phone);
        
        // Additional validation: check for letters in original phone
        if (preg_match('/[a-zA-Z]/', $phone)) {
            return false;
        }
        
        switch ($countryCode) {
            case 'ID':
                // Indonesian phone numbers
                // Mobile: 08xx-xxxx-xxxx or +62-8xx-xxxx-xxxx (must be 081x, 082x, etc, not 070x)
                if (preg_match('/^(\+62|62|0)8[1-9]\d{7,10}$/', $cleanPhone)) {
                    return true;
                }
                // Landline: 021-xxxx-xxxx (area code 02x, not 07x)
                if (preg_match('/^(\+62|62|0)[2-6]\d{7,9}$/', $cleanPhone)) {
                    return true;
                }
                return false;
                
            case 'US':
                // US phone numbers: +1-xxx-xxx-xxxx
                return preg_match('/^(\+1|1)?\d{10}$/', $cleanPhone);
                
            default:
                throw new InvalidArgumentException("Unsupported country code: {$countryCode}");
        }
    }
    
    /**
     * Sanitize and validate input with edge cases
     */
    public static function sanitizeInput(string $input, int $maxLength = 255): string
    {
        if (strlen($input) > $maxLength) {
            throw new InvalidArgumentException("Input exceeds maximum length of {$maxLength}");
        }
        
        // Handle null bytes (security edge case)
        if (strpos($input, "\0") !== false) {
            throw new InvalidArgumentException('Input contains null bytes');
        }
        
        // Strip dangerous characters but preserve Unicode
        $sanitized = filter_var($input, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        
        // Handle excessive whitespace
        $sanitized = preg_replace('/\s+/', ' ', trim($sanitized));
        
        return $sanitized;
    }
}
