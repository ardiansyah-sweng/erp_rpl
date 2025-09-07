<?php

namespace App\Constants;

class Messages
{
    // Branch messages ID
    public const BRANCH_NOT_FOUND = 'Cabang tidak ditemukan';
    public const BRANCH_CREATED = 'Cabang berhasil ditambahkan!';
    public const BRANCH_UPDATED = 'Cabang berhasil diupdate!';
    public const BRANCH_FAILED_TO_UPDATED = 'Failed to update branch';
    public const BRANCH_DELETED = 'Cabang berhasil dihapus!';
    public const BRANCH_IN_USE = 'Cabang tidak bisa dihapus karena masih digunakan di tabel lain!';
    public const BRANCH_DELETE_FAILED = 'Gagal menghapus cabang!';

    // Warehouse messages
    public const WAREHOUSE_NOT_FOUND = 'Gudang tidak ditemukan';
    public const WAREHOUSE_CREATED = 'Gudang berhasil ditambahkan!';
    public const WAREHOUSE_UPDATED = 'Gudang berhasil diupdate!';
    public const WAREHOUSE_DELETED = 'Gudang berhasil dihapus!';
    public const WAREHOUSE_IN_USE = 'Gudang tidak bisa dihapus karena masih digunakan di tabel lain!';
    public const WAREHOUSE_DELETE_FAILED = 'Gagal menghapus gudang!';

    // Merk messages
    public const MERK_NOT_FOUND = 'Merk tidak ditemukan';
    public const MERK_CREATED = 'Merk berhasil ditambahkan!';
    public const MERK_UPDATED = 'Merk berhasil diupdate!';
    public const MERK_DELETED = 'Merk berhasil dihapus!';
    public const MERK_IN_USE = 'Merk tidak bisa dihapus karena masih digunakan di tabel lain!';
    public const MERK_DELETE_FAILED = 'Gagal menghapus merk!';

    // General
    public const ACTION_FAILED = 'Aksi gagal dilakukan!';
}
