<?php

use App\Http\Controllers\AdminApi\Catalog\DG\DgProductController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'products'], function () {
    Route::get('', [DgProductController::class, 'index']);
    Route::post('', [DgProductController::class, 'create']);
    Route::group(['prefix' => '{product_id}'], function () {
        Route::get('', [DgProductController::class, 'get']);
        Route::put('', [DgProductController::class, 'update']);
        Route::delete('', [DgProductController::class, 'delete']);
    });
});
