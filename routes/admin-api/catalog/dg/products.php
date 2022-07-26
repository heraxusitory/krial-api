<?php

use App\Http\Controllers\AdminApi\Catalog\DG\DgProductController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'products'], function () {
    Route::get('', [DgProductController::class, 'index']);
    Route::post('', [DgProductController::class, 'create']);
    Route::post('change_active', [DgProductController::class, 'changeActive']);
    Route::post('set_series', [DgProductController::class, 'setSeries']);
    Route::group(['prefix' => '{product_id}'], function () {
        Route::get('', [DgProductController::class, 'get']);
        Route::put('', [DgProductController::class, 'update']);
        Route::put('update_product_row', [DgProductController::class, 'updateProductRow']);
        Route::delete('', [DgProductController::class, 'delete']);
    });
});
