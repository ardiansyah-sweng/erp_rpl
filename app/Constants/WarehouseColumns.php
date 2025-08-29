<?php

namespace App\Constants;

class WarehouseColumns
{
    public const ID                 = 'id';
    public const NAME               = 'warehouse_name';
    public const ADDRESS            = 'warehouse_address';
    public const PHONE              = 'warehouse_phone';
    public const IS_RM_WAREHOUSE    = 'is_rm_warehouse';
    public const IS_FG_WAREHOUSE    = 'is_fg_warehouse';
    public const IS_ACTIVE          = 'is_active';
    public const CREATED_AT         = 'created_at';
    public const UPDATED_AT         = 'updated_at';

    /**
     * Get fillable columns (exclude id, created_at, updated_at)
     */    
    public static function getFillable(): array
    {
        return [
            self::NAME,
            self::ADDRESS,
            self::PHONE,
            self::IS_RM_WAREHOUSE,
            self::IS_FG_WAREHOUSE,
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
            self::IS_RM_WHOUSE,
            self::IS_FG_WHOUSE,
            self::IS_ACTIVE,
            self::CREATED_AT,
            self::UPDATED_AT,
        ];
    }

}
