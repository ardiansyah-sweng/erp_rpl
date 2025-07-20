<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BillOfMaterial;
// use Test\TestCase;

class BillOfMaterialController extends Controller
{
    // Fungsi untuk menghapus Bill of Material berdasarkan id
    public function show($id)
    {
        // Hapus data berdasarkan id
        $bomData= $this->getBillOfMaterial($id);

        if ($bomData->isEmpty()){
            return response()->json(['message'=>'Data not found.'],404);
        }

        return response()->json($bomData);
    }

    private function getBillOfMaterial($id)
    {
        return BillOfMaterial::where('id',$id)->get();
    }

    //fungsi untuk menghapus Bill of Material berdasarkan id
    public function deleteBillOfMaterial($id)
    {
        //hapus data berdasarkan id
        $deleted=DB::table('bill_of_material')->where('id',$id)->delete();
        //response JSON 
        if ($deleted){
            return response()->json(['message'=> 'Bill of Material deleted successfully.'],200);
        }else{
            return response()->json(['message'=> 'Bill of Material not found.'],404);
        }
    }
}   