<?php

namespace App\Constants;

class MerkColumns
{
    // Table columns (sesuai dengan tabel yang sudah ada)
    public const ID                 = 'id';
    public const MERK              = 'merk';  // Kolom utama nama merk
    public const IS_ACTIVE         = 'is_active';
    public const CREATED_AT        = 'created_at';
    public const UPDATED_AT        = 'updated_at';

    // Alias untuk konsistensi dengan kode yang sudah dibuat
    public const MERK_NAME         = self::MERK;
    public const MERK_DESCRIPTION  = null; // Tidak ada di tabel ini

    /**
     * Get fillable columns (exclude id, created_at, updated_at)
     */
    public static function getFillable(): array
    {
        return [
            self::MERK,
            self::IS_ACTIVE,
        ];
    }

    /**
     * Get searchable columns
     */
    public static function getSearchable(): array
    {
        return [
            self::MERK,
        ];
    }

    /**
     * Get all columns
     */
    public static function getAll(): array
    {
        return [
            self::ID,
            self::MERK,
            self::IS_ACTIVE,
            self::CREATED_AT,
            self::UPDATED_AT,
        ];
    }
}
