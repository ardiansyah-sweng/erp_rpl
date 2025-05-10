<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIProductController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SupplierPIController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MerkController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplierMaterialController;
use App\Helpers\EncryptionHelper;

// =====================
// AUTH & HOME ROUTES
// =====================
Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// =====================
// STATIC VIEW ROUTES
// =====================
Route::view('/branches', 'branches.index')->name('branches.index');
Route::view('/supplier/pic/add', 'supplier.pic.add')->name('supplier.pic.add');
Route::view('/supplier/add', 'supplier.add')->name('supplier.add');
Route::view('/supplier/detail', 'supplier.detail')->name('supplier.detail');
Route::view('/supplier/material/add', 'supplier.material.add')->name('supplier.material.add');
Route::view('/branch/create', 'branch.add')->name('branch.create');
Route::view('/item/create', 'item.add')->name('item.create');
Route::get('/product/add', function () {
    return view('product/add');
});
Route::get('/supplier/list', function () {
    return view('supplier.list');
});
Route::get('/item/add', function () {
    return view('item/add');
});
Route::get('/branch/add', function () {
    return view('branch/add');
});

// =====================
// PRODUCT ROUTES
// =====================
Route::prefix('product')->group(function () {
    Route::get('/list', [ProductController::class, 'getProductList'])->name('product.list');
    Route::get('/detail/{id}', [ProductController::class, 'getProductById'])->name('product.detail');
    Route::post('/add', [ProductController::class, 'addProduct'])->name('product.add');
    Route::post('/addProduct', [ProductController::class, 'addProduct'])->name('product.addproduct');
});

// =====================
// API ROUTES
// =====================
Route::get('/products', [APIProductController::class, 'getProducts'])->name('api.products');
Route::get('/prices', [APIProductController::class, 'getAvgBasePrice'])->name('api.prices');
Route::get('/api/branches/{id}', [BranchController::class, 'getBranchById'])->name('api.branch.detail');

// =====================
// BRANCH ROUTES
// =====================
Route::prefix('branch')->group(function () {
    Route::get('/', [BranchController::class, 'getBranchAll'])->name('branch.list');
    Route::post('/add', [BranchController::class, 'addBranch'])->name('branch.add');
    Route::get('/{id}', [BranchController::class, 'getBranchByID'])->name('branch.detail');
});

// =====================
// PURCHASE ORDER ROUTES
// =====================
Route::prefix('purchase_orders')->group(function () {
    Route::get('/', [PurchaseOrderController::class, 'getPurchaseOrder'])->name('purchase.orders');
    Route::get('/raw/{id}', [PurchaseOrderController::class, 'getPurchaseOrderByID'])->name('purchase.orders.raw');
    Route::get('/detail/{encrypted_id}', function ($encrypted_id) {
        $id = EncryptionHelper::decrypt($encrypted_id);
        return app()->make(PurchaseOrderController::class)->getPurchaseOrderByID($id);
    })->name('purchase.orders.detail');
    Route::get('/search', [PurchaseOrderController::class, 'searchPurchaseOrder'])->name('purchase_orders.search');
    Route::post('/add', [PurchaseOrderController::class, 'addPurchaseOrder'])->name('purchase_orders.add');
    Route::get('/length/{po_number}/{order_date}', [PurchaseOrderController::class, 'getPOLength'])->name('purchase_orders.length');
});

// =====================
// SUPPLIER PIC ROUTES
// =====================
Route::get('/supplier/pic/detail/{id}', [SupplierPIController::class, 'getPICByID']);
Route::put('/supplier/pic/update/{id}', [SupplierPIController::class, 'update'])->name('supplier.pic.update');
Route::get('/supplier/pic/list', function () {
    $pics = App\Models\SupplierPic::getSupplierPICAll(10);
    return view('supplier.pic.list', compact('pics'));
})->name('supplier.pic.list');

// =====================
// ITEM ROUTES
// =====================
Route::prefix('item')->group(function () {
    Route::get('/', [ItemController::class, 'getItemList'])->name('item.list');
    Route::post('/add', [ItemController::class, 'store'])->name('item.add');
    Route::delete('/{id}', [ItemController::class, 'deleteItem'])->name('item.delete');
});
Route::get('/items', [ItemController::class, 'getItemAll'])->name('items.all');

// =====================
// MERK ROUTES
// =====================
Route::prefix('merk')->group(function () {
    Route::get('/{id}', [MerkController::class, 'getMerkById'])->name('merk.detail');
    Route::post('/add', [MerkController::class, 'addMerk'])->name('merk.add');
    Route::post('/update/{id}', [MerkController::class, 'updateMerk'])->name('merk.update');
    Route::delete('/{id}', [MerkController::class, 'deleteMerk'])->name('merk.delete');
});

// =====================
// SUPPLIER MATERIAL
// =====================
Route::get('/supplier/material', [SupplierMaterialController::class, 'getSupplierMaterial'])->name('supplier.material');
Route::post('/supplier/material/add', [SupplierMaterialController::class, 'addSupplierMaterial'])->name('supplier.material.add');
Route::get('/supplier/material/list', [SupplierMaterialController::class, 'getSupplierMaterial'])->name('supplier.material.list');

// =====================
// CATEGORY
// =====================
Route::get('/category/print', [CategoryController::class, 'printCategoryPDF'])->name('category.print');
