<?php

namespace App\Http\Controllers;
use App\Models\Product;

use Illuminate\Http\Request;

class APIProductController extends Controller
{
    public function getProducts()
    {
        $products = Product::all();
        return response()->json($products);
    }
}
