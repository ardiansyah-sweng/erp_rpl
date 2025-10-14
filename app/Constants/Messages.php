<?php

namespace App\Constants;

class Messages
{
    // Branch messages ID
    public const BRANCH_NAME_EMPTY = 'Nama cabang wajib diisi.';
    public const BRANCH_NAME_NOT_TEXT = 'Nama cabang harus berupa teks.';
    public const BRANCH_NAME_TOO_SHORT = 'Nama cabang minimal 3 karakter.';
    public const BRANCH_NAME_TOO_LONG = 'Nama cabang maksimal 50 karakter.';
    public const BRANCH_NAME_EXISTS = 'Nama cabang sudah ada, silakan gunakan nama lain.';
   
    public const BRANCH_ADDRESS_EMPTY = 'Alamat cabang wajib diisi.';
    public const BRANCH_ADDRESS_NOT_TEXT = 'Alamat cabang harus berupa teks.';
    public const BRANCH_ADDRESS_TOO_SHORT = 'Alamat cabang minimal 3 karakter.';
    public const BRANCH_ADDRESS_TOO_LONG = 'Alamat cabang maksimal 100 karakter.';
    public const BRANCH_ADDRESS_EXISTS = 'Alamat cabang sudah ada, silakan gunakan nama lain.';

    public const BRANCH_PHONE_EMPTY = 'Telepon cabang wajib diisi.';
    public const BRANCH_PHONE_NOT_TEXT = 'Telepon cabang harus berupa teks.';
    public const BRANCH_PHONE_TOO_SHORT = 'Telepon cabang minimal 3 karakter.';
    public const BRANCH_PHONE_TOO_LONG = 'Telepon cabang maksimal 30 karakter.';

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

    // Category messages
    public const CATEGORY_NOT_FOUND = 'Kategori tidak ditemukan';
    public const CATEGORY_CREATED = 'Kategori berhasil ditambahkan!';
    public const CATEGORY_UPDATED = 'Kategori berhasil diupdate!';
    public const CATEGORY_DELETED = 'Kategori berhasil dihapus!';
    public const CATEGORY_IN_USE = 'Kategori tidak bisa dihapus karena masih digunakan oleh produk!';
    public const CATEGORY_HAS_CHILDREN = 'Kategori tidak bisa dihapus karena memiliki sub-kategori!';
    public const CATEGORY_DELETE_FAILED = 'Gagal menghapus kategori!';
    public const CATEGORY_CIRCULAR_DEPENDENCY = 'Kategori tidak bisa menjadi parent dari dirinya sendiri!';
    public const CATEGORY_INVALID_PARENT = 'Parent kategori yang dipilih tidak valid!';
    public const CATEGORY_NAME_EXISTS = 'Nama kategori sudah digunakan!';
    public const CATEGORY_NAME_TOO_SHORT = 'Nama kategori minimal 3 karakter!';
    public const CATEGORY_NAME_INVALID = 'Nama kategori hanya boleh berisi huruf, angka, spasi, dash, underscore, dan titik!';

    // General
    public const ACTION_FAILED = 'Aksi gagal dilakukan!';
}
