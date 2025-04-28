<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIProductController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MerkController;
use App\Http\Controllers\MerkController_salman;
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
Route::get('/branch/add', function () {
    return view('branch/add');
});

// Supplier views
Route::get('/supplier/pic/add', function () {
    return view('supplier/pic/add');
});
Route::get('/supplier/detail', function () {
    return view('supplier/detail');
});
Route::get('/supplier/material/add', function () {
    return view('supplier/material/add');
});

// Product
Route::get('/product/list', [ProductController::class, 'getProductList'])->name('product.list');

// API
Route::get('/products', [APIProductController::class, 'getProducts'])->name('api.products');
Route::get('/prices', [APIProductController::class, 'getAvgBasePrice'])->name('api.prices');
Route::get('/api/branches/{id}', [BranchController::class, 'getBranchById'])->name('api.branch.detail');

// Branch
Route::get('/branch', [BranchController::class, 'getBranchAll'])->name('branch.list');
Route::post('/branch/add', [BranchController::class, 'addBranch'])->name('branch.add');
Route::get('/branch/{id}', [BranchController::class, 'getBranchByID'])->name('branch.detail');

// Purchase Orders
Route::get('/purchase-orders', [PurchaseOrderController::class, 'getPurchaseOrder'])->name('purchase.orders');
Route::get('/purchase-orders/{id}', [PurchaseOrderController::class, 'getPurchaseOrderByID'])->name('purchase_orders.detail');
Route::get('/purchase-orders/search', [PurchaseOrderController::class, 'searchPurchaseOrder'])->name('purchase_orders.search');
Route::post('/purchase-orders/add', [PurchaseOrderController::class, 'addPurchaseOrder'])->name('purchase_orders.add');

// Items
Route::get('/items', [ItemController::class, 'getItemAll'])->name('item.all');
Route::get('/item', [ItemController::class, 'getItemList'])->name('item.list');
Route::delete('/item/{id}', [ItemController::class, 'deleteItem'])->name('item.delete');

// Merk
Route::get('/merk/{id}', [MerkController::class, 'getMerkById'])->name('merk.detail');
Route::get('/merks', [MerkController_salman::class, 'index'])->name('merk.index');

// Laporan
Route::get('/laporan/cabang/pdf', [LaporanController::class, 'generateCabangPDF'])->name('laporan.cabang.pdf');

// Supplier (optional, uncomment jika perlu)
// Route::get('/supplier/{id}', [SupplierController::class, 'getUpdateSupplier'])->name('supplier.update');
