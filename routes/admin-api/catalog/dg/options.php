<?php

use App\Http\Controllers\AdminApi\Catalog\DG\DgOptionController;
use App\Http\Controllers\AdminApi\Catalog\DG\DgOptionGroupController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'options'], function () {
    Route::get('', [DgOptionController::class, 'index']);
    Route::post('', [DgOptionController::class, 'create']);
    Route::group(['prefix' => '{option_id}'], function () {
        Route::get('', [DgOptionController::class, 'get']);
        Route::put('', [DgOptionController::class, 'update']);
        Route::delete('', [DgOptionController::class, 'delete']);
    });
});
