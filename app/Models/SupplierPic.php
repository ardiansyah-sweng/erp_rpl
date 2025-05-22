<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    public static function addSupplierPIC($supplierID, $data)
    {
        $data['supplier_id'] = $supplierID;
        return self::create($data);
<<<<<<< HEAD
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

=======
    } 
    
    public static function assignmentDuration($pic)
    {
        if (!$pic->assigned_date) {
            return 'Tanggal penugasan tidak tersedia';
        }

        $startDate = Carbon::parse($pic->assigned_date);
        $now = Carbon::now();

        $diff = $startDate->diff($now);

        return json_encode([
            'years' => $diff->y,
            'months' => $diff->m,
            'days' => $diff->d,
        ]);
    }
>>>>>>> 3aafc76a573fe35c3b366b64e8d8c1b6d0add789
}
