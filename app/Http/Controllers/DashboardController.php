<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $jumlahKategori = Category::countCategory();
        return view('dashboard.index', compact('jumlahKategori'));
    }
}
