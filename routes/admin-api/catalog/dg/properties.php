<?php

use App\Http\Controllers\AdminApi\Catalog\DG\DgPropertyController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'properties'], function () {
    Route::get('', [DgPropertyController::class, 'index']);
    Route::get('filter_types', [DgPropertyController::class, 'getFilterTypes']);
    Route::group(['prefix' => '{property_group_id}'], function () {
        Route::put('', [DgPropertyController::class, 'update']);
    });
});
