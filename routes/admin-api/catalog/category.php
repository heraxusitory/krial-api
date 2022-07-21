<?php

use App\Http\Controllers\AdminApi\Catalog\CategoryController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'categories'], function () {
    Route::get('', [CategoryController::class, 'index']);
    Route::group(['prefix' => '{category_id}'], function () {
        Route::get('', [CategoryController::class, 'get']);
        Route::put('', [CategoryController::class, 'update']);
    });
});
