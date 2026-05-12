<?php

namespace App\Helpers;

class CurrencyHelper
{
    /**
     * Format amount to currency string
     */
    public static function format(float $amount, string $currency = 'IDR'): string
    {
        if ($amount < 0) {
            throw new \InvalidArgumentException('Amount cannot be negative');
        }
        
        switch ($currency) {
            case 'IDR':
                return 'Rp ' . number_format($amount, 0, ',', '.');
            case 'USD':
                return '$' . number_format($amount, 2, '.', ',');
            default:
                throw new \InvalidArgumentException("Unsupported currency: {$currency}");
        }
    }
    
    /**
     * Parse currency string to amount
     */
    public static function parse(string $currencyString): float
    {
        // Remove currency symbols (Rp, $, etc.)
        $cleaned = preg_replace('/[^\d.,]/', '', $currencyString);
        
        // Handle different number formats
        if (strpos($cleaned, '.') !== false && strpos($cleaned, ',') !== false) {
            // Format like 1,234.56 (USD style) - comma is thousands separator
            $cleaned = str_replace(',', '', $cleaned);
        } elseif (substr_count($cleaned, '.') > 1) {
            // Format like 1.500.000 (IDR style) - dots are thousands separators
            $cleaned = str_replace('.', '', $cleaned);
        } elseif (strpos($cleaned, ',') !== false && strlen(explode(',', $cleaned)[1] ?? '') <= 2) {
            // Format like 1234,56 (European style) - comma is decimal separator
            $cleaned = str_replace(',', '.', $cleaned);
        }
        // If only dots and no commas, and last dot has 1-2 digits after, it's decimal
        elseif (strpos($cleaned, '.') !== false && preg_match('/\.(\d{1,2})$/', $cleaned)) {
            // Keep as is - it's already in the right format
        }
        // Otherwise, remove all dots (they are thousands separators)
        elseif (strpos($cleaned, '.') !== false) {
            $cleaned = str_replace('.', '', $cleaned);
        }
        
        return (float) $cleaned;
    }
    
    /**
     * Calculate percentage
     */
    public static function calculatePercentage(float $amount, float $percentage): float
    {
        if ($percentage < 0 || $percentage > 100) {
            throw new \InvalidArgumentException('Percentage must be between 0 and 100');
        }
        
        return round($amount * ($percentage / 100), 2);
    }
}