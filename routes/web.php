<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIProductController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MerkController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SupplierController;

// Dashboard
Route::get('/', function () {
    return view('dashboard');
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Branch views
Route::get('/branches', function () {
    return view('branches.index');
})->name('branches.index');
Route::get('/branch/create', function () {
    return view('branch.create');
})->name('branch.create');

// Supplier views
Route::get('/supplier/pic/create', function () {
    return view('supplier.pic.create');
})->name('supplier.pic.create');
Route::get('/supplier/detail', function () {
    return view('supplier.detail');
})->name('supplier.detail');
Route::get('/supplier/material/create', function () {
    return view('supplier.material.create');
})->name('supplier.material.create');

// Product
Route::get('/products/list', [ProductController::class, 'getProductList'])->name('products.list');

// API
Route::get('/api/products', [APIProductController::class, 'getProducts'])->name('api.products');
Route::get('/api/prices', [APIProductController::class, 'getAvgBasePrice'])->name('api.prices');
Route::get('/api/branches/{id}', [BranchController::class, 'getBranchById'])->name('api.branch.detail');

// Branch
Route::get('/branches', [BranchController::class, 'getBranchAll'])->name('branch.list');
Route::post('/branch/create', [BranchController::class, 'addBranch'])->name('branch.create');
Route::get('/branch/{id}', [BranchController::class, 'getBranchByID'])->name('branch.detail');

// Purchase Orders
Route::get('/purchase-orders', [PurchaseOrderController::class, 'getPurchaseOrder'])->name('purchase.orders');
Route::get('/purchase-orders/{id}', [PurchaseOrderController::class, 'getPurchaseOrderByID'])->name('purchase_orders.detail');
Route::get('/purchase-orders/search', [PurchaseOrderController::class, 'searchPurchaseOrder'])->name('purchase_orders.search');
Route::post('/purchase-orders/create', [PurchaseOrderController::class, 'addPurchaseOrder'])->name('purchase_orders.create');

// Items
Route::get('/items', [ItemController::class, 'getItemAll'])->name('items.all');
Route::get('/items/{id}', [ItemController::class, 'getItemById'])->name('items.detail');  // Rute baru untuk item berdasarkan ID
Route::delete('/items/{id}', [ItemController::class, 'deleteItem'])->name('items.delete');

// Merk
Route::get('/merks/{id}', [MerkController::class, 'getMerkById'])->name('merks.detail');
Route::get('/merks', [MerkController::class, 'index'])->name('merks.index');

// Laporan
Route::get('/laporan/cabang/pdf', [LaporanController::class, 'generateCabangPDF'])->name('laporan.cabang.pdf');

// Supplier
// Route::get('/supplier/{id}', [SupplierController::class, 'getUpdateSupplier'])->name('supplier.update');
