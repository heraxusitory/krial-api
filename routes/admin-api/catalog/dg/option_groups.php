<?php

use App\Http\Controllers\AdminApi\Catalog\DG\DgOptionGroupController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'option_groups'], function () {
    Route::get('', [DgOptionGroupController::class, 'index']);
    Route::post('', [DgOptionGroupController::class, 'create']);
    Route::group(['prefix' => '{option_group_id}'], function () {
        Route::get('', [DgOptionGroupController::class, 'get']);
        Route::put('', [DgOptionGroupController::class, 'update']);
        Route::delete('', [DgOptionGroupController::class, 'delete']);
    });
});
