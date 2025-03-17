<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIProductController;
use App\Http\Controllers\BranchController;

Route::get('/', function () {
    return view('dashboard');
});
Route::get('/dashboard', function () {
    return view('dashboard');
});
Route::get('/PIC_add', function () {
    return view('supplier/pic/add');
});



#API
Route::get('/products', [APIProductController::class, 'getProducts']);
Route::get('/prices', [APIProductController::class, 'getAvgBasePrice']);
Route::get('/branches/{id}', [App\Http\Controllers\BranchController::class, 'getBranchById']);