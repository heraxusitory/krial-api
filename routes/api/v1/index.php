<?php

use App\Http\Controllers\API\v1\Catalog\DgProductController;
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
});
