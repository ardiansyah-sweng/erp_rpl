<?php

namespace App\Constants;

class MerkColumns
{
    public const ID             = 'id';
    public const NAME           = 'merk_name';
    public const DESCRIPTION    = 'merk_description';
    public const IS_ACTIVE      = 'is_active';
    public const CREATED_AT     = 'created_at';
    public const UPDATED_AT     = 'updated_at';

    /**
     * Get fillable columns (exclude id, created_at, updated_at)
     */
    public static function getFillable(): array
    {
        return [
            self::NAME,
            self::DESCRIPTION,
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
            self::DESCRIPTION,
            self::IS_ACTIVE,
            self::CREATED_AT,
            self::UPDATED_AT,
        ];
    }

    /**
     * Get searchable columns
     */
    public static function getSearchable(): array
    {
        return [
            self::NAME,
            self::DESCRIPTION,
        ];
    }
}
