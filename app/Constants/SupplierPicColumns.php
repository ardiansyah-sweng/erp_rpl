<?php

namespace App\Constants;

class SupplierPicColumns
{
    public const ID             = 'id';
    public const SUPPLIER_ID    = 'supplier_id';
    public const NAME           = 'name';
    public const PHONE          = 'phone_number';
    public const EMAIL          = 'email';
    public const IS_ACTIVE      = 'is_active';
    public const AVATAR         = 'avatar';
    public const ASSIGNED_DATE  = 'assigned_date';
    public const CREATED_AT     = 'created_at';
    public const UPDATED_AT     = 'updated_at';

    /**
     * Get fillable columns (exclude id, created_at, updated_at)
     */
    public static function getFillable(): array
    {
        return [
            self::SUPPLIER_ID,
            self::NAME,
            self::PHONE,
            self::EMAIL,
            self::IS_ACTIVE,
            self::ASSIGNED_DATE,
        ];
    }

    /**
     * Get all columns
     */
    public static function getAll(): array
    {
        return [
            self::ID,
            self::SUPPLIER_ID,
            self::NAME,
            self::PHONE,
            self::EMAIL,
            self::IS_ACTIVE,
            self::AVATAR,
            self::ASSIGNED_DATE,
            self::CREATED_AT,
            self::UPDATED_AT,
        ];
    }
}
