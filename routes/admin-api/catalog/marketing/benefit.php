<?php

use App\Http\Controllers\AdminApi\Catalog\Marketing\BenefitController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'benefits'], function () {
    Route::get('', [BenefitController::class, 'index']);
    Route::post('', [BenefitController::class, 'create']);
    Route::group(['prefix' => '{benefit_id}'], function () {
        Route::get('', [BenefitController::class, 'get']);
        Route::put('', [BenefitController::class, 'update']);
        Route::delete('', [BenefitController::class, 'delete']);
    });
});
