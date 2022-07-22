<?php

use App\Http\Controllers\API\v1\Catalog\CategoryController;
use App\Http\Controllers\API\v1\Catalog\DgProductController;
use App\Http\Controllers\API\v1\Shop\ApplicationRequestController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'catalog'], function () {
    Route::group(['prefix' => 'dg'], function () {
//        Route::get('', [DgProductController::class, 'index']);
        Route::match(['GET', 'POST'], '', [DgProductController::class, 'index']);
        Route::group(['prefix' => '{dg_product_id}'], function () {
            Route::get('', [DgProductController::class, 'get']);
            Route::get('group_options', [DgProductController::class, 'groupOptions']);
        });
    });

    Route::group(['prefix' => 'categories'], function () {
        Route::get('', [CategoryController::class, 'index']);
        Route::group(['prefix' => '{category_code}'], function () {
            Route::get('', [CategoryController::class, 'getByCode']);
            Route::get('banners', [CategoryController::class, 'getBanners']);
        });
    });
});

Route::group(['prefix' => 'shop'], function () {
    Route::group(['prefix' => 'application_requests'], function () {
        Route::post('', [ApplicationRequestController::class, 'create'])->middleware('throttle:2,1');
    });
});
