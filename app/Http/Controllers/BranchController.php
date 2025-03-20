<?php

namespace App\Http\Controllers;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function getBranchById($id){
        return (new Branch)->getBranchById($id);
    }
    public function getBranchAll()
    {
        $branches = Branch::paginate(10);
        return view('branch.list', ['branches' => $branches]);
    }
}

