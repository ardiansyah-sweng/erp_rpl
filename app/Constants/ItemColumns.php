<?php

namespace App\Constants;

class ItemColumns
{
    public const ID             = 'id';
    public const PROD_ID        = 'product_id';
    public const SKU            = 'sku';
    public const NAME           = 'name';
    public const MEASUREMENT    = 'measurement';
    public const BASE_PRICE     = 'base_price';
    public const SELLING_PRICE  = 'selling_price';
    public const PURCHASE_UNIT  = 'purchase_unit';
    public const SELL_UNIT      = 'sell_unit';
    public const STOCK_UNIT     = 'stock_unit';
    public const CREATED_AT     = 'created_at';
    public const UPDATED_AT     = 'updated_at';

    /**
     * Get fillable columns (exclude id, created_at, updated_at)
     */
    public static function getFillable(): array
    {
        return [
            self::PROD_ID,
            self::SKU,
            self::NAME,
            self::MEASUREMENT,
            self::BASE_PRICE,
            self::SELLING_PRICE,
            self::PURCHASE_UNIT,
            self::SELL_UNIT,
            self::STOCK_UNIT,
        ];
    }

    /**
     * Get all columns
     */
    public static function getAll(): array
    {
        return [
            self::ID,
            self::PROD_ID,
            self::SKU,
            self::NAME,
            self::MEASUREMENT,
            self::BASE_PRICE,
            self::SELLING_PRICE,
            self::PURCHASE_UNIT,
            self::SELL_UNIT,
            self::STOCK_UNIT,
            self::CREATED_AT,
            self::UPDATED_AT,
        ];
    }

}
