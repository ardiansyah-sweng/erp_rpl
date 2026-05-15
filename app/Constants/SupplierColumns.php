<?php

namespace App\Constants;

class SupplierColumns
{
    public const ID             = 'id';
    public const SUPPLIER_ID    = 'supplier_id';
    public const COMPANY_NAME   = 'company_name';
    public const ADDRESS        = 'address';
    public const PHONE          = 'telephone';
    public const BANK_ACCOUNT   = 'bank_account';
    public const CREATED_AT     = 'created_at';
    public const UPDATED_AT     = 'updated_at';

    /**
     * Get fillable columns (exclude id, created_at, updated_at)
     */
    public static function getFillable(): array
    {
        return [
            self::SUPPLIER_ID,
            self::COMPANY_NAME,
            self::ADDRESS,
            self::PHONE,
            self::BANK_ACCOUNT,
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
            self::COMPANY_NAME,
            self::ADDRESS,
            self::PHONE,
            self::BANK_ACCOUNT,
            self::CREATED_AT,
            self::UPDATED_AT,
        ];
    }
}
