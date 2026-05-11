<?php

namespace App\Constants;

class PurchaseOrderDetailColumns
{
    public const PO_NUMBER     = 'po_number';
    public const PRODUCT_ID    = 'product_id';
    public const BASE_PRICE    = 'base_price';
    public const QUANTITY      = 'quantity';
    public const AMOUNT        = 'amount';
    public const RECEIVED_DAYS = 'received_days';
    public const CREATED_AT    = 'created_at';
    public const UPDATED_AT    = 'updated_at';

    /**
     * Get fillable columns (exclude primary keys, created_at, updated_at)
     */
    public static function getFillable(): array
    {
        return [
            self::BASE_PRICE,
            self::QUANTITY,
            self::AMOUNT,
            self::RECEIVED_DAYS,
        ];
    }

    /**
     * Get all columns
     */
    public static function getAll(): array
    {
        return [
            self::PO_NUMBER,
            self::PRODUCT_ID,
            self::BASE_PRICE,
            self::QUANTITY,
            self::AMOUNT,
            self::RECEIVED_DAYS,
            self::CREATED_AT,
            self::UPDATED_AT,
        ];
    }
}
