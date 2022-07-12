<?php

use App\Http\Controllers\API\v1\Catalog\DgProductController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'catalog'], function () {
    Route::group(['prefix' => 'dg'], function () {
        Route::get('', [DgProductController::class, 'index']);
        Route::get('{dg_product_id}', [DgProductController::class, 'get']);
    });
});
