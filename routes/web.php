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

#Branch
Route::get('/purchase_orders', [PurchaseOrderController::class, 'getPurchaseOrder'])->name('purchase.orders');

#route form dan validate perlu controller untuk melakukan testing layout view
Route::get('/supplier/pic/detail', function () {
    return view('supplier.pic.detail');
});