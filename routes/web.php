<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/products', [APIProductController::class, 'getProducts']);