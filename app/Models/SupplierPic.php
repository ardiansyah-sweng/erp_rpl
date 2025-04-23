<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierPic extends Model
{
    protected $table = 'supplier_pic';
    protected $fillable = ['nama', 'email', 'telepon', 'supplier_id'];

    public static function deleteSupplierPICByID($id)
    {
        $pic = self::find($id);
        if ($pic) {
            $pic->delete();
            return true;
        }
        return false;
    }
}
