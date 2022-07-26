<?php

use App\Http\Controllers\AdminApi\Catalog\DG\DGSeriesController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'series'], function () {
    Route::get('', [DGSeriesController::class, 'index']);
    Route::post('', [DGSeriesController::class, 'create']);
    Route::group(['prefix' => '{dg_series_id}'], function () {
        Route::get('', [DGSeriesController::class, 'get']);
        Route::put('', [DGSeriesController::class, 'update']);
        Route::delete('', [DGSeriesController::class, 'delete']);
    });
});
