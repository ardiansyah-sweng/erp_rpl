<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIProductController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;

Route::get('/', function () {
    return view('dashboard');
});
Route::get('/dashboard', function () {
    return view('dashboard');
});
Route::get('/supplier/pic/add', function () {
    return view('supplier/pic/add');
});
Route::get('/branch/add', function () {
    return view('branch/add');
});

Route::get('/Supplier', function (){
    return view('Supplier');
});

# Product 
Route::get('/product/list', [ProductController::class, 'getProductList'])->name('product.list'); 

# API
Route::get('/products', [APIProductController::class, 'getProducts']);
Route::get('/prices', [APIProductController::class, 'getAvgBasePrice']);
Route::get('/branches/{id}', [BranchController::class, 'getBranchById']);

# Branch
Route::get('/purchase_orders', [PurchaseOrderController::class, 'getPurchaseOrder'])->name('purchase.orders');
Route::get('/branch', [BranchController::class, 'getBranchAll'])->name('branch.list');
Route::post('/branch/add', [BranchController::class, 'addBranch'])->name('branch.add');

# Items
Route::get('/items', [App\Http\Controllers\ItemController::class, 'getItemAll']);

# supplier
Route::get('/supplier/{id}', [SupplierController::class, 'deleteSupplierByID']);