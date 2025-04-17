<?php

namespace App\Http\Controllers;

use App\Models\MerkModel;
use Illuminate\Http\Request;

class MerkController extends Controller
{
    public function index()
    {
        $data = (new MerkModel)->getMerk();
        return response()->json($data);
    }
}
