<?php

namespace App\Models;

use App\Models\SupplierPIC;

class SupplierPICModel
{
    public static function deleteSupplierPICByID($id)
    {
        $pic = SupplierPIC::find($id);

        if ($pic) {
            $pic->delete();
            return true;
        }

        return false;
    }
}
