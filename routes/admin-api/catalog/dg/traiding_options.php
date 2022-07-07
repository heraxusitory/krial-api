<?php

use App\Http\Controllers\AdminApi\Catalog\DG\DGTradingOptionController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'traiding_options'], function () {
    Route::get('', [DGTradingOptionController::class, 'index']);
//    Route::group(['prefix' => '{manufacture_id}'], function () {
//        Route::delete('', [DgManufactureController::class, 'delete']);
//    });
});
