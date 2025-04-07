<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIProductController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/branches', function () {
    return view('branches.index');
})->name('branches.index');

Route::get('/supplier/pic/add', function () {
    return view('supplier/pic/add');
});

Route::get('/branch/add', function () {
    return view('branch/add');
});
