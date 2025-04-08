<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIProductController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\ProductController;

use App\Http\Controllers\PurchaseOrderController;

Route::get('/purchase/orders', [PurchaseOrderController::class, 'getPurchaseOrder'])->name('purchase.orders');


Route::get('/', function () {
    return view('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('product.list');

Route::get('/branches', [BranchController::class, 'index'])->name('branch.list');


Route::get('/supplier/pic/add', function () {
    return view('supplier/pic/add');
});

Route::get('/branch/add', function () {
    return view('branch/add');
});
