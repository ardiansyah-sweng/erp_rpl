<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIProductController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\PurchaseOrderController;


Route::get('/', function () {
    return view('dashboard');
});
Route::get('/dashboard', function () {
    return view('dashboard');
});

#API
Route::get('/products', [APIProductController::class, 'getProducts']);
Route::get('/prices', [APIProductController::class, 'getAvgBasePrice']);
Route::get('/branches/{id}', [App\Http\Controllers\BranchController::class, 'getBranchById']);

#Branch mengarah ke halaman list.blade.php
// Route::get('/purchase/orders', [PurchaseOrderController::class, 'getPurchaseOrder']);
Route::get('/purchase/orders', [PurchaseOrderController::class, 'getPurchaseOrder'])->name('purchase.orders');