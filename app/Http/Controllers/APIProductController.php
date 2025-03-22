<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\LogAvgBasePrice;

use Illuminate\Http\Request;

class APIProductController extends Controller
{
    public function getProducts()
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function getAvgBasePrice()
    {
        $avgBasePrices = LogAVgBasePrice::all();
        return response()->json($avgBasePrices);
    }
}
