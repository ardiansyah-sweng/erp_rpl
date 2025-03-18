<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIProductController;
use App\Http\Controllers\BranchController;

Route::get('/', function () {
    return view('dashboard');
});
Route::get('/dashboard', function () {
    return view('dashboard');
});

# API Routes
Route::get('/products', [APIProductController::class, 'getProducts']);
Route::get('/prices', [APIProductController::class, 'getAvgBasePrice']);
Route::get('/branches/{id}', [BranchController::class, 'getBranchById']);

# Route untuk menambah branch
Route::post('/branch/add', [BranchController::class, 'addBranch']);
