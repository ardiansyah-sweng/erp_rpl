<?php

namespace App\Constants;

class CustomerColumns
{
    public const ID          = 'id';
    public const NAME        = 'customer_name';
    public const ADDRESS     = 'customer_address';
    public const PHONE       = 'customer_phone';
    public const IS_ACTIVE   = 'is_active';
    public const CREATED_AT  = 'created_at';
    public const UPDATED_AT  = 'updated_at';

    /**
     * Get fillable columns (exclude id, created_at, updated_at)
     */
    public static function getFillable(): array
    {
        return [
            self::NAME,
            self::ADDRESS,
            self::PHONE,
            self::IS_ACTIVE,
        ];
    }

    /**
     * Get all columns
     */
    public static function getAll(): array
    {
        return [
            self::ID,
            self::NAME,
            self::ADDRESS,
            self::PHONE,
            self::IS_ACTIVE,
            self::CREATED_AT,
            self::UPDATED_AT,
        ];
    }
}
