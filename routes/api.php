<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\ProductController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('api.token')->group(function () {
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/brands', [BrandController::class, 'index']);
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/faqs', [FaqController::class, 'index']);
    Route::get('/products', [ProductController::class, 'index']);
});