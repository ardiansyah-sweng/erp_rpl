<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIProductController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\SupplierMaterialController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('dashboard');
});
Route::get('/branches', function () {
    return view('branches.index');
})->name('branches.index');
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
Route::get('/supplier/pic/add', function () {
    return view('supplier/pic/add');
});
Route::get('/branch/add', function () {
    return view('branch/add');
});

#API
Route::get('/products', [APIProductController::class, 'getProducts']);
Route::get('/prices', [APIProductController::class, 'getAvgBasePrice']);
Route::get('/branches/{id}', [App\Http\Controllers\BranchController::class, 'getBranchById']);
Route::get('/supplier-material/{id}', [SupplierMaterialController::class, 'getSupplierMaterialByID']); // widya_d_2200018266
# Product 
Route::get('/product/list', [ProductController::class, 'getProductList'])->name('product.list'); 

# API
Route::get('/products', [APIProductController::class, 'getProducts'])->name('api.products');
Route::get('/prices', [APIProductController::class, 'getAvgBasePrice'])->name('api.prices');
Route::get('/api/branches/{id}', [BranchController::class, 'getBranchById'])->name('api.branch.detail');

# Branch
Route::get('/purchase_orders', [PurchaseOrderController::class, 'getPurchaseOrder'])->name('purchase.orders');
Route::get('/branch', [BranchController::class, 'getBranchAll'])->name('branch.list');
Route::post('/branch/add', [BranchController::class, 'addBranch'])->name('branch.add');
Route::get('/branch/{id}', [BranchController::class, 'getBranchByID'])->name('branch.detail');

# PurchaseOrders
Route::get('/purchase_orders/{id}', [PurchaseOrderController::class, 'getPurchaseOrderByID']);

# Items
Route::get('/items', [App\Http\Controllers\ItemController::class, 'getItemAll']);
