<?php

use App\Http\Controllers\AdminApi\Catalog\DG\DgManufactureController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'manufacturers'], function () {
    Route::get('', [DgManufactureController::class, 'index']);
//    Route::group(['prefix' => '{manufacture_id}'], function () {
//        Route::delete('', [DgManufactureController::class, 'delete']);
//    });
});
