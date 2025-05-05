<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierPic extends Model
{
    protected $table = 'supplier_pic';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['name', 'email', 'password'];


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Tetapkan nama tabel dan kolom
        $this->table = config('db_constants.table.supplier_pic');
        $this->fillable = array_values(config('db_constants.column.supplier_pic') ?? []);
    }

    public static function updateSupplierPIC($id, $data)
    {
        $supplierPic = self::find($id);

        if (!$supplierPic) {
            return [
                'status' => 'error',
                'message' => 'Supplier PIC tidak ditemukan.',
                'code' => 404
            ];
        }

        $supplierPic->fill($data);

        if ($supplierPic->save()) {
            return [
                'status' => 'success',
                'message' => 'Supplier PIC berhasil diperbarui.',
                'data' => $supplierPic,
                'code' => 200
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Gagal memperbarui Supplier PIC.',
                'code' => 500
            ];
        }
    }
}
