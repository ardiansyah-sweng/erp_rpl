<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIProductController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ItemController; 
use App\Http\Controllers\MerkController;
use App\Http\Controllers\SupplierController;




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
Route::get('/supplier/detail', function () {
    return view('supplier/detail');
});
Route::get('/branch/add', function () {
    return view('branch/add');
});
Route::get('/supplier/material/add', function () {
    return view('supplier/material/add');
});

# Product
Route::get('/product/list', [ProductController::class, 'getProductList'])->name('product.list');
Route::get('/product/add', [ProductController::class, 'create'])->name('product.create');
Route::post('/product/addProduct', [ProductController::class, 'addProduct'])->name('product.addproduct');



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
Route::get('/purchase-orders/search', [PurchaseOrderController::class, 'searchPurchaseOrder'])->name('purchase_orders.search');
Route::post('/purchase_orders/add', [PurchaseOrderController::class, 'addPurchaseOrder'])->name('purchase_orders.add'); // tambahan

# Items
Route::get('/items', [ItemController::class, 'getItemAll']);
Route::get('/item', [ItemController::class, 'getItemList'])->name('item.list'); // untuk tampilan
Route::delete('/item/{id}', [ItemController::class, 'deleteItem'])->name('item.delete');

# Merk
Route::get('/merk/{id}', [MerkController::class, 'getMerkById']);

#Supplier
#Route::get('/supplier/{id}', [SupplierController::class, 'getUpdateSupplier']);