<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIProductController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MerkController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplierMaterialController;
use App\Helpers\EncryptionHelper;

# Login
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/branches', function () {
    return view('branches.index');
})->name('branches.index');

# Supplier group
Route::prefix('supplier')->group(function () {
    Route::get('pic/add', function () {
        return view('supplier/pic/add');
    });

    Route::get('add', function () {
        return view('supplier/add');
    });

    Route::get('detail', function () {
        return view('supplier/detail');
    });

    Route::get('material/add', function () {
        return view('supplier/material/add');
    });

    Route::get('material', [SupplierMaterialController::class, 'getSupplierMaterial'])
         ->name('supplier.material');
});

# Branch add view
Route::get('/branch/add', function () {
    return view('branch/add');
})->name('branch.add');

# Purchase Order Detail (encrypted ID)
Route::get('/purchase_orders/detail/{encrypted_id}', function ($encrypted_id) {
    $id = EncryptionHelper::decrypt($encrypted_id);
    return app()->make(PurchaseOrderController::class)
                ->getPurchaseOrderByID($id);
})->name('purchase.orders.detail');

# Item add view
Route::get('/item/add', function () {
    return view('item/add');
})->name('item.add');

# Product routes
Route::get('/product/list', [ProductController::class, 'getProductList'])
     ->name('product.list');
Route::get('/product/detail/{id}', [ProductController::class, 'getProductById'])
     ->name('product.detail');

# API routes
Route::get('/products', [APIProductController::class, 'getProducts'])
     ->name('api.products');
Route::get('/prices', [APIProductController::class, 'getAvgBasePrice'])
     ->name('api.prices');
Route::get('/api/branches/{id}', [BranchController::class, 'getBranchById'])
     ->name('api.branch.detail');

# Branch controller routes
Route::get('/purchase_orders', [PurchaseOrderController::class, 'getPurchaseOrder'])
     ->name('purchase.orders');
Route::get('/branch', [BranchController::class, 'getBranchAll'])
     ->name('branch.list');
Route::post('/branch/add', [BranchController::class, 'addBranch'])
     ->name('branch.add');
Route::get('/branch/{id}', [BranchController::class, 'getBranchByID'])
     ->name('branch.detail');

# Purchase Orders controller
Route::get('/purchase_orders/{id}', [PurchaseOrderController::class, 'getPurchaseOrderByID']);
Route::get('/purchase-orders/search', [PurchaseOrderController::class, 'searchPurchaseOrder'])
     ->name('purchase_orders.search');
Route::post('/purchase_orders/add', [PurchaseOrderController::class, 'addPurchaseOrder'])
     ->name('purchase_orders.add');

# Item controller routes
Route::get('/items', [ItemController::class, 'getItemAll']);
Route::get('/item', [ItemController::class, 'getItemList'])
     ->name('item.list');
Route::delete('/item/{id}', [ItemController::class, 'deleteItem'])
     ->name('item.delete');

# Merk controller route
Route::get('/merk/{id}', [MerkController::class, 'getMerkById'])
     ->name('merk.detail');

# Cetak Pdf
Route::get('/items/pdf', [ItemController::class, 'generateItemPDF'])->name('items.pdf');

