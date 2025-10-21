<?php

namespace App\Models;

class WarehouseColumns
{
    // Nama kolom utama
    public const NAME = 'warehouse_name';
    public const ADDRESS = 'warehouse_address';
    public const PHONE = 'warehouse_telephone';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';

    // Tambahkan kolom status dan tipe gudang
    public const IS_ACTIVE = 'is_active';
    public const IS_RM_WAREHOUSE = 'is_rm_whouse';
    public const IS_FG_WAREHOUSE = 'is_fg_whouse';

    // Untuk keperluan fillable di model
    public static function getFillable()
    {
        return [
            self::NAME,
            self::ADDRESS,
            self::PHONE,
            self::IS_ACTIVE,
            self::IS_RM_WAREHOUSE,
            self::IS_FG_WAREHOUSE,
        ];
    }
}
