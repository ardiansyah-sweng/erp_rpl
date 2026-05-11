<?php

use Illuminate\Support\Facades\Route;
use App\Helpers\EncryptionHelper;

// Controllers
use App\Http\Controllers\APIProductController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierPIController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MerkController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplierMaterialController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\AssortProductionController;
use App\Http\Controllers\BillOfMaterialController;
use App\Http\Controllers\GoodsReceiptNoteController;

// Models
use App\Models\Warehouse;
use App\Models\BillOfMaterial;

/*
|--------------------------------------------------------------------------
| BASIC ROUTE
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => redirect()->route('login'));

Route::get('/login', fn() => view('login'))->name('login');

Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

/*
|--------------------------------------------------------------------------
| VIEW ONLY
|--------------------------------------------------------------------------
*/

Route::get('/merk/add', fn() => view('merk.add'));
Route::get('/branches/index', fn() => view('branches.index'))->name('branches.index');
Route::get('/branches/add', fn() => view('branches.add'));
Route::get('/supplier/pic/add', fn() => view('supplier/pic/add'));
Route::get('/supplier/add', fn() => view('supplier/add'));
Route::get('/supplier/detail', fn() => view('supplier/detail'));
Route::get('/branch/update', fn() => view('branch/update'));
Route::get('/supplier/material/add', fn() => view('supplier/material/add'));
Route::get('/item/add', fn() => view('item/add'));
Route::get('/product/add', fn() => view('product/add'));
Route::get('/supplier/material/detail', fn() => view('supplier/material/detail'));
Route::get('/goods_receipt_note/add', fn() => view('goods_receipt_note/add'));
Route::get('/goods_receipt_note/detail', fn() => view('goods_receipt_note/detail'));
Route::get('/warehouse/add', fn() => view('warehouse/add'))->name('warehouse.add');
Route::get('product/category/detail', fn() => view('product/category/detail'));
Route::get('/assortment_production/detail', fn() => view('assortment_production.detail'));
Route::get('/supplier/{id}/material-by-type', [SupplierController::class, 'countSupplierMaterialByType'])->name('supplier.material.bytype');

/*
|--------------------------------------------------------------------------
| SUPPLIER MATERIAL (🔥 FITUR KAMU ADA DI SINI)
|--------------------------------------------------------------------------
*/

Route::get('/supplier/material', [SupplierMaterialController::class, 'getSupplierMaterial'])->name('supplier.material');

Route::post('/supplier/material/add', [SupplierMaterialController::class, 'addSupplierMaterial'])->name('supplier.material.add');

Route::post('/supplier/material/update/{id}', [SupplierMaterialController::class, 'updateSupplierMaterial'])->name('supplier.material.update');

Route::get('/supplier/material/{id}', [SupplierMaterialController::class, 'getSupplierMaterialById'])->name('supplier.material.detail');

Route::get('/supplier-material/{supplier_id}/{product_type}', [SupplierMaterialController::class, 'getSupplierMaterialByProductType']);
Route::get('/branch/list', function () {
    return "Branch page";
})->name('branch.list');
Route::get('/supplier/{supplier}/category-view', 
    [SupplierMaterialController::class, 'showCategoryCountView']);
//  FITUR TUGAS
// ambil data by type
Route::get('/supplier-material/{supplier_id}/{product_type}', 
    [SupplierMaterialController::class, 'getSupplierMaterialByProductType']);

// hitung by category (AMAN)
Route::get('/supplier-material/category/{category}/{supplier}', 
    [SupplierMaterialController::class, 'countSupplierMaterialByCategory']);
    
// Cetak PDF
Route::get('/supplier/{supplier_id}/cetak-pdf', [SupplierMaterialController::class, 'cetakPDF']);

/*
|--------------------------------------------------------------------------
| SUPPLIER
|--------------------------------------------------------------------------
*/

Route::get('/supplier/list', [SupplierController::class, 'listSuppliers'])->name('supplier.list');

Route::get('/supplier/detail/{id}', [SupplierController::class, 'getSupplierById'])->name('supplier.detail');

Route::post('/supplier/add', [SupplierController::class, 'AddSuplier'])->name('supplier.add');

Route::put('/supplier/update/{id}', [SupplierController::class, 'updateSupplier'])->name('supplier.updateSupplier');

Route::get('/suppliers/search', [SupplierController::class, 'searchSuppliers']);

/*
|--------------------------------------------------------------------------
| ITEM
|--------------------------------------------------------------------------
*/

Route::get('/item', [ItemController::class, 'getItemList'])->name('item.list');

Route::get('/item/{id}', [ItemController::class, 'getItemById']); // ✅ FIXED

Route::post('/item/add', [ItemController::class, 'addItem'])->name('item.add');

Route::put('/item/update/{id}', [ItemController::class, 'updateItem']);

Route::delete('/item/{id}', [ItemController::class, 'deleteItem'])->name('item.delete');

/*
|--------------------------------------------------------------------------
| PRODUCT
|--------------------------------------------------------------------------
*/

Route::get('/product/list', [ProductController::class, 'getProductList'])->name('product.list');

Route::get('/product/detail/{id}', [ProductController::class, 'getProductById'])->name('product.detail');

Route::post('/product/add', [ProductController::class, 'addProduct'])->name('product.add');

Route::get('/product/search/{keyword}', [ProductController::class, 'searchProduct']);

Route::get('/products/type/{type}', [ProductController::class, 'getProductByType']);

/*
|--------------------------------------------------------------------------
| PURCHASE ORDER
|--------------------------------------------------------------------------
*/

Route::get('/purchase_orders', [PurchaseOrderController::class, 'getPurchaseOrder'])->name('purchase.orders');

Route::get('/purchase_orders/{id}', [PurchaseOrderController::class, 'getPurchaseOrderByID']);

Route::post('/purchase_orders/add', [PurchaseOrderController::class, 'addPurchaseOrder']);

Route::get('/purchase_orders/detail/{encrypted_id}', function ($encrypted_id) {
    $id = EncryptionHelper::decrypt($encrypted_id);
    return app()->make(PurchaseOrderController::class)->getPurchaseOrderByID($id);
});

/*
|--------------------------------------------------------------------------
| WAREHOUSE
|--------------------------------------------------------------------------
*/

Route::get('/warehouse/list', [WarehouseController::class, 'getWarehouseAll'])->name('warehouse.list');

Route::post('/warehouse/add', [WarehouseController::class, 'addWarehouse']);

Route::delete('/warehouse/delete/{id}', [WarehouseController::class, 'deleteWarehouse']);

/*
|--------------------------------------------------------------------------
| CATEGORY
|--------------------------------------------------------------------------
*/

Route::get('/category', [CategoryController::class, 'getCategoryList'])->name('category.list');

Route::delete('/category/delete/{id}', [CategoryController::class, 'deleteCategory']);

/*
|--------------------------------------------------------------------------
| MERK
|--------------------------------------------------------------------------
*/

Route::get('/merks', [MerkController::class, 'getMerkAll'])->name('merk.list');

Route::post('/merk/add', [MerkController::class, 'addMerk']);

Route::delete('/merk/delete/{id}', [MerkController::class, 'deleteMerk']);

/*
|--------------------------------------------------------------------------
| PRODUCTION
|--------------------------------------------------------------------------
*/

Route::get('/production', [AssortProductionController::class, 'getProduction']);

Route::post('/assort-production/add', [AssortProductionController::class, 'addProduction']);

/*
|--------------------------------------------------------------------------
| BILL OF MATERIAL
|--------------------------------------------------------------------------
*/

Route::get('/bill-of-material', [BillOfMaterialController::class, 'getBillOfMaterial']);

Route::post('/billofmaterial/add', [BillOfMaterialController::class, 'addBillOfMaterial']);

/*
|--------------------------------------------------------------------------
| GOODS RECEIPT NOTE
|--------------------------------------------------------------------------
*/

Route::post('/goods-receipt-note', [GoodsReceiptNoteController::class, 'addGoodsReceiptNote']);