<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIProductController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index']);

Route::get('/', function () {
    return view('dashboard');
});
Route::get('/dashboard', function () {
    return view('dashboard');
});

#API
Route::get('/products', [APIProductController::class, 'getProducts']);
Route::get('/prices', [APIProductController::class, 'getAvgBasePrice']);
Route::get('/branches/{id}', [App\Http\Controllers\BranchController::class, 'getBranchById']);
