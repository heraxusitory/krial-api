<?php


use App\Http\Controllers\AdminApi\Shop\ApplicationRequestController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'application_requests'], function () {
    Route::get('', [ApplicationRequestController::class, 'index']);
//    Route::post('', [ApplicationRequestController::class, 'create']);
    Route::group(['prefix' => '{request_id}'], function () {
        Route::get('', [ApplicationRequestController::class, 'get']);
        Route::put('', [ApplicationRequestController::class, 'update']);
//        Route::delete('', [ApplicationRequestController::class, 'delete']);
    });
});
