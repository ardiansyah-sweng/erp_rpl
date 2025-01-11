<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIProductController;

Route::get('/', function () {
    return view('welcome');
});

#API
Route::get('/products', [APIProductController::class, 'getProducts']);
Route::get('/prices', [APIProductController::class, 'getAvgBasePrice']);