<?php

use App\Http\Controllers\AdminApi\Catalog\DG\DgEngineManufactureController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'engine_manufacturers'], function () {
    Route::get('', [DgEngineManufactureController::class, 'index']);
//    Route::group(['prefix' => '{manufacture_id}'], function () {
//        Route::delete('', [DgManufactureController::class, 'delete']);
//    });
});
