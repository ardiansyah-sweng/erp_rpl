<?php

namespace App\Http\Controllers;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function getBranchById($id){
        // $branch = (new Branch)->getBranchById($id);
        // if(!$branch){
        //     return response()->json([
        //         'message' => 'Branch not found'
        //     ], 404);
        // }
        // return response()->json($branch);
        // dd($branch->toArray());

        return (new Branch)->getBranchById($id);
    }
}
