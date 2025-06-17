<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIProductController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierPIController; // perubahan
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController; // tambahkan jika belum
use App\Http\Controllers\MerkController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplierMaterialController;
use App\Helpers\EncryptionHelper;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\AssortProductionController;
use App\Http\Controllers\BillOfMaterialController;//BOM

#Login
Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/login', function () {
    return view('login'); // tampilkan view login
})->name('login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/branches', function () {
    return view('branches.index');
})->name('branches.index');
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
Route::get('/supplier/pic/add', function () {
    return view('supplier/pic/add');
});

Route::get('/supplier/add', function () {
    return view('supplier/add');
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
Route::get('/purchase_orders/detail/{encrypted_id}', function($encrypted_id) {
    $id = EncryptionHelper::decrypt($encrypted_id);
    return app()->make(PurchaseOrderController::class)->getPurchaseOrderByID($id);
})->name('purchase.orders.detail');
Route::get('/item/add', function () {
    return view('item/add');
});
// Dikonfirmasi oleh chiqitita_C_163 - route form tambah produk sudah tersedia
Route::get('/product/add', function () {
    return view('product/add');
});
Route::get('/supplier/list', function () {
    return view('supplier.list');
});
Route::get('/supplier/material/detail', function () {
    return view('supplier/material/detail');
});


# Product
Route::get('/product/list', [ProductController::class, 'getProductList'])->name('product.list');

Route::get('/product/detail/{id}', [ProductController::class, 'getProductById'])->name('product.detail');
Route::post('/product/add', [ProductController::class, 'addProduct'])->name('product.add');
Route::post('/product/addProduct', [ProductController::class, 'addProduct'])->name('product.addproduct');

#Product Update 
Route::put('/product/update/{id}', [ProductController::class, 'updateProduct'])->name('product.updateProduct'); //Sudah sesuai pada ERP RPL
Route::get('/product/update/{id}', [ProductController::class, 'updateProduct'])->name('product.updateProduct');

# API
Route::get('/products', [APIProductController::class, 'getProducts'])->name('api.products');
Route::get('/prices', [APIProductController::class, 'getAvgBasePrice'])->name('api.prices');
Route::get('/api/branches/{id}', [BranchController::class, 'getBranchById'])->name('api.branch.detail');

# Branch
Route::get('/purchase_orders', [PurchaseOrderController::class, 'getPurchaseOrder'])->name('purchase.orders');
Route::get('/branch', [BranchController::class, 'getBranchAll'])->name('branch.list');
Route::post('/branch/add', [BranchController::class, 'addBranch'])->name('branch.add');
Route::delete('/branch/{id}', [BranchController::class, 'deleteBranch'])->name('branch.delete');
Route::get('/branch/{id}', [BranchController::class, 'getBranchByID'])->name('branch.detail');

# PurchaseOrders
Route::get('/purchase_orders/{id}', [PurchaseOrderController::class, 'getPurchaseOrderByID']);
Route::get('/purchase-orders/search', [PurchaseOrderController::class, 'searchPurchaseOrder'])->name('purchase_orders.search');
Route::post('/purchase_orders/add', [PurchaseOrderController::class, 'addPurchaseOrder'])->name('purchase_orders.add'); // tambahan
Route::get('/purchase_orders/detail/{encrypted_id}', function ($encrypted_id) {
    $id = EncryptionHelper::decrypt($encrypted_id);
    return app()->make(PurchaseOrderController::class)->getPurchaseOrderByID($id);
})->name('purchase.orders.detail');
Route::get('/po-length/{po_number}/{order_date}', [PurchaseOrderController::class, 'getPOLength'])
    ->name('purchase_orders.length');
 

# supplier pic route nya
Route::get('/supplier/pic/detail/{id}', [SupplierPIController::class, 'getPICByID']);
Route::put('/supplier/pic/update/{id}', [SupplierPIController::class, 'update'])->name('supplier.pic.update'); //tanbahkan update
Route::get('/supplier/pic/list', function () {
    $pics = App\Models\SupplierPic::getSupplierPICAll(10);
    return view('supplier.pic.list', compact('pics')); //implementasi sementara(menunggu controller dari faiz el fayyed)
})->name('supplier.pic.list');
Route::get('/supplier/pic/search', [SupplierPIController::class, 'searchSupplierPic'])->name('supplier.pic.list');
Route::post('/supplier/{supplierID}/add-pic', [SupplierPIController::class, 'addSupplierPIC'])->name('supplier.pic.add');


# Items
Route::get('/items', [ItemController::class, 'getItemAll']);
Route::get('/item', [ItemController::class, 'getItemList'])->name('item.list'); // untuk tampilan
Route::delete('/item/{id}', [ItemController::class, 'deleteItem'])->name('item.delete');

Route::post('/item/add', [ItemController::class, 'store'])->name('item.add');
Route::put('/item/update/{id}', [ItemController::class, 'updateItem']);

Route::post('/item/add', [ItemController::class, 'addItem'])->name('item.add');
Route::get('/item/add', [ItemController::class, 'showAddForm'])->name('item.add');
Route::get('/item/{id}', [itemController::class, 'getItemById']);


# Merk
Route::get('/merk/{id}', [MerkController::class, 'getMerkById'])->name('merk.detail');
Route::post('/merk/add', [MerkController::class, 'addMerk'])->name('merk.add');
Route::post('/merk/update/{id}', [MerkController::class, 'updateMerk'])->name('merk.add');
Route::get('/merks', [MerkController::class, 'getMerkAll'])->name('merk.list');

#Supplier
Route::get('/supplier/material', [SupplierMaterialController::class, 'getSupplierMaterial'])->name('supplier.material');
Route::post('/supplier/material/add', [SupplierMaterialController::class, 'addSupplierMaterial'])->name('supplier.material.add');
Route::get('/supplier/material/list', [SupplierMaterialController::class, 'getSupplierMaterial'])->name('supplier.material.list');
Route::post('/supplier/material/update/{id}', [SupplierMaterialController::class, 'updateSupplierMaterial'])->name('supplier.material.update');
Route::get('/supplier/detail/{id}', [SupplierController::class, 'getSupplierById'])->name('Supplier.detail');

#Suppplier Update 
Route::put('/supplier/update/{id}', [SupplierController::class, 'updateSupplier'])->name('supplier.updateSupplier');//Sudah sesuai pada ERP RPL
Route::get('/supplier/update/{id}', [SupplierController::class, 'updateSupplier'])->name('supplier.updateSupplier');

#Cetak pdf
Route::get('/category/print', [CategoryController::class, 'printCategoryPDF'])->name('category.print');

#Category
Route::put('/category/update/{id}', [CategoryController::class, 'updateCategory'])->name('category.detail');
Route::get('/category/{id}', [CategoryController::class, 'getCategoryById']);
Route::delete('/category/delete/{id}', [CategoryController::class, 'deleteCategory'])->name('category.delete');

#Supplier Pic
Route::delete('/supplier/pic/delete/{id}', [SupplierPIController::class, 'deleteSupplierPIC'])->name('supplier.pic.delete');

# Warehouse
Route::get('/warehouse/detail/{id}', [WarehouseController::class, 'getWarehouseById']);

# Bill of Material - BOM
Route::get('/bom', [BillOfMaterialController::class, 'index'])->name('bom.list');//Tampilkan daftar BOM --> Untuk View BOM