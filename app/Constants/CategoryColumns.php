<?php

namespace App\Constants;

class CategoryColumns
{
    public const ID             = 'id';
    public const CATEGORY       = 'category';
    public const PARENT         = 'parent_id';
    public const IS_ACTIVE      = 'is_active';
    public const CREATED_AT     = 'created_at';
    public const UPDATED_AT     = 'updated_at';

    /**
     * Get fillable columns (exclude id, created_at, updated_at)
     */
    public static function getFillable(): array
    {
        return [
            self::CATEGORY,
            self::PARENT,
            self::IS_ACTIVE,
        ];
    }
}
