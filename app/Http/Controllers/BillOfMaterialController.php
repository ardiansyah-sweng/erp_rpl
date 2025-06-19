<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BillOfMaterialModel;

class BillOfMaterialController extends Controller
{
    public function destroy($id)
    {
        $deleted = BillOfMaterialModel::deleteBillOfMaterial($id);

        if ($deleted) {
            return response()->json(['message' => 'Bill of Material berhasil dihapus.']);
        }

        return response()->json(['message' => 'Data tidak ditemukan.'], 404);
    }
}
