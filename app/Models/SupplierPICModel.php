<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierPICModel extends Model
{
    use HasFactory;

    protected $table = 'supplier_pics';

    protected $fillable = [
        'supplier_id',
        'name',
        'phone_number',
        'email',
        'assigned_date',
    ];

    public static function updateSupplierPIC($id, array $data)
    {
        $pic = self::find($id);
        if ($pic) {
            $pic->update($data);
            return [
                'status'  => 'success',
                'message' => 'Data PIC berhasil diperbarui!',
                'data'    => $pic,
                'code'    => 200
            ];
        }

        return [
            'status'  => 'error',
            'message' => 'Data PIC tidak ditemukan.',
            'code'    => 404
        ];
    }
}