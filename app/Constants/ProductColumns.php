<?php

namespace App\Constants;

class ProductColumns
{
    public const ID             = 'id';
    public const PRODUCT_ID     = 'product_id';
    public const NAME           = 'product_name';
    public const TYPE           = 'product_type';
    public const CATEGORY       = 'product_category';
    public const DESC           = 'product_description';
    public const CREATED_AT     = 'created_at';
    public const UPDATED_AT     = 'updated_at';

    /**
     * Get fillable columns (exclude id, created_at, updated_at)
     */
    public static function getFillable(): array
    {
        return [
            self::PRODUCT_ID,
            self::NAME,
            self::TYPE,
            self::CATEGORY,
            self::DESC,
        ];
    }

    /**
     * Get all columns
     */
    public static function getAll(): array
    {
        return [
            self::ID,
            self::PRODUCT_ID,
            self::NAME,
            self::TYPE,
            self::CATEGORY,
            self::DESC,
            self::CREATED_AT,
            self::UPDATED_AT,
        ];
    }

}
