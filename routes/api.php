<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BranchController;

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
