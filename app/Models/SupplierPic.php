<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierPic extends Model
{
    // Nama tabel dan kolom yang dapat diisi (mass-assignment)
    protected $table;
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Tetapkan nama tabel dari config
        $this->table = config('db_constants.table.supplier_pic');

        // Tetapkan kolom-kolom yang bisa diisi (fillable) dari config
        $this->fillable = array_values(config('db_constants.column.supplier_pic') ?? []);
    }

    /**
     * Menambahkan data PIC Supplier ke database.
     *
     * @param array $data
     * @return SupplierPic|null
     */
    public static function addSupplierPIC(array $data): ?SupplierPic
    {
        try {
            // Lakukan penyimpanan dan kembalikan objek model yang baru disimpan
            return self::create($data);
        } catch (\Exception $e) {
            // Logging jika perlu: \Log::error($e);
            return null; // Atau bisa throw exception kalau mau
        }
    }
}
