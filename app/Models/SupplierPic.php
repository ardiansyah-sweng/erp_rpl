<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierPic extends Model
{
    protected $table;
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('db_constants.table.supplier_pic');
        $this->fillable = array_values(config('db_constants.column.supplier_pic') ?? []);
    }

    // method untuk ambil data berdasarkan ID
    public static function getPICByID($id)
    {
        return self::find($id);
    }

    // relasi ke Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public static function getSupplierPICAll($perPage = 10)
    {
        return self::paginate($perPage);
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
