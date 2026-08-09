<?php

namespace App\Constants;

/**
 * Konstanta untuk kolom tabel activity_logs.
 * Mengikuti pola yang sudah ada (BranchColumns, WarehouseColumns, dll).
 */
class ActivityLogColumns
{
    const ID = 'id';
    const USER_ID = 'user_id';
    const USER_NAME = 'user_name';
    const ACTION = 'action';
    const MODULE = 'module';
    const DESCRIPTION = 'description';
    const TARGET_ID = 'target_id';
    const IP_ADDRESS = 'ip_address';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // Nilai aksi yang valid
    const ACTION_CREATE = 'create';
    const ACTION_UPDATE = 'update';
    const ACTION_DELETE = 'delete';

    // Daftar modul yang tersedia
    const MODULE_BRANCH = 'branch';
    const MODULE_WAREHOUSE = 'warehouse';
    const MODULE_SUPPLIER = 'supplier';
    const MODULE_SUPPLIER_PIC = 'supplier_pic';
    const MODULE_SUPPLIER_MATERIAL = 'supplier_material';
    const MODULE_PURCHASE_ORDER = 'purchase_order';
    const MODULE_PRODUCT = 'product';
    const MODULE_ITEM = 'item';
    const MODULE_CATEGORY = 'category';
    const MODULE_MERK = 'merk';
    const MODULE_BOM = 'bill_of_material';
    const MODULE_PRODUCTION = 'production';
    const MODULE_GRN = 'goods_receipt_note';
    const MODULE_GOODS_RETURN = 'goods_return';
    const MODULE_USER = 'user';
    const MODULE_PO_PAYMENT = 'po_payment';

    /**
     * Mendapatkan daftar kolom yang bisa diisi (fillable).
     */
    public static function getFillable(): array
    {
        return [
            self::USER_ID,
            self::USER_NAME,
            self::ACTION,
            self::MODULE,
            self::DESCRIPTION,
            self::TARGET_ID,
            self::IP_ADDRESS,
        ];
    }

    /**
     * Mendapatkan daftar aksi yang valid.
     */
    public static function getActions(): array
    {
        return [
            self::ACTION_CREATE => 'Create',
            self::ACTION_UPDATE => 'Update',
            self::ACTION_DELETE => 'Delete',
        ];
    }

    /**
     * Mendapatkan daftar modul yang tersedia.
     */
    public static function getModules(): array
    {
        return [
            self::MODULE_BRANCH => 'Branch',
            self::MODULE_WAREHOUSE => 'Warehouse',
            self::MODULE_SUPPLIER => 'Supplier',
            self::MODULE_SUPPLIER_PIC => 'Supplier PIC',
            self::MODULE_SUPPLIER_MATERIAL => 'Supplier Material',
            self::MODULE_PURCHASE_ORDER => 'Purchase Order',
            self::MODULE_PRODUCT => 'Product',
            self::MODULE_ITEM => 'Item',
            self::MODULE_CATEGORY => 'Category',
            self::MODULE_MERK => 'Merk',
            self::MODULE_BOM => 'Bill of Material',
            self::MODULE_PRODUCTION => 'Production',
            self::MODULE_GRN => 'Goods Receipt Note',
            self::MODULE_GOODS_RETURN => 'Goods Return',
            self::MODULE_USER => 'User',
            self::MODULE_PO_PAYMENT => 'Pembayaran PO',
        ];
    }
}
