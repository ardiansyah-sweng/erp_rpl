<?php

namespace App\Http\Controllers;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function getBranchById($id){
        return (new Branch)->getBranchById($id);
    }
}
