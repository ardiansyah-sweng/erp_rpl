<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\MerkController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Branch API Routes - Using unified BranchController
Route::prefix('branches')->name('api.branches.')->group(function () {

    // Custom endpoints (place specific routes before dynamic routes)
    Route::get('/filter/active', [BranchController::class, 'active'])->name('active');
    Route::get('/analytics/statistics', [BranchController::class, 'statistics'])->name('statistics');
    Route::post('/bulk/update-status', [BranchController::class, 'bulkUpdateStatus'])->name('bulk.update.status');
    Route::get('/search/advanced', [BranchController::class, 'search'])->name('search');
    
    // Basic CRUD operations
    Route::get('/', [BranchController::class, 'index'])->name('index');
    Route::post('/', [BranchController::class, 'store'])->name('store');
    Route::get('/{id}', [BranchController::class, 'show'])->name('show');
    Route::put('/{id}', [BranchController::class, 'update'])->name('update');
    Route::delete('/{id}', [BranchController::class, 'destroy'])->name('destroy');
});

// Warehouse API Routes - Using unified WarehouseController
Route::prefix('warehouses')->name('api.warehouses.')->group(function () {
    
    // Custom endpoints (place specific routes before dynamic routes)
    Route::get('/filter/active', [WarehouseController::class, 'active'])->name('active');
    Route::get('/filter/rm', [WarehouseController::class, 'rawMaterialWarehouses'])->name('raw.material');
    Route::get('/filter/fg', [WarehouseController::class, 'finishedGoodsWarehouses'])->name('finished.goods');
    Route::get('/analytics/statistics', [WarehouseController::class, 'statistics'])->name('statistics');
    Route::post('/bulk/update-status', [WarehouseController::class, 'bulkUpdateStatus'])->name('bulk.update.status');
    Route::get('/search/advanced', [WarehouseController::class, 'searchWarehouse'])->name('search');
    
    // Basic CRUD operations
    Route::get('/', [WarehouseController::class, 'index'])->name('index');
    Route::post('/', [WarehouseController::class, 'store'])->name('store');
    Route::get('/{id}', [WarehouseController::class, 'show'])->name('show');
    Route::put('/{id}', [WarehouseController::class, 'update'])->name('update');
    Route::delete('/{id}', [WarehouseController::class, 'destroy'])->name('destroy');
});

// Merk API Routes - Using unified MerkController
Route::prefix('merk')->name('api.merk.')->group(function () {
    
    // Custom endpoints (place specific routes before dynamic routes)
    Route::get('/filter/active', [MerkController::class, 'active'])->name('active');
    Route::get('/analytics/statistics', [MerkController::class, 'statistics'])->name('statistics');
    Route::post('/bulk/update-status', [MerkController::class, 'bulkUpdateStatus'])->name('bulk.update.status');
    Route::get('/search/advanced', [MerkController::class, 'search'])->name('search');
    
    // Basic CRUD operations
    Route::get('/', [MerkController::class, 'index'])->name('index');
    Route::post('/', [MerkController::class, 'store'])->name('store');
    Route::get('/{id}', [MerkController::class, 'show'])->name('show');
    Route::put('/{id}', [MerkController::class, 'update'])->name('update');
    Route::delete('/{id}', [MerkController::class, 'destroy'])->name('destroy');
});
