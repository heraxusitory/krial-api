<?php

use App\Http\Controllers\AdminApi\Catalog\DG\DgPropertyController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'properties'], function () {
    Route::get('', [DgPropertyController::class, 'index']);
    Route::group(['prefix' => '{property_group_id}'], function () {
        Route::put('', [DgPropertyController::class, 'update']);
    });
});
