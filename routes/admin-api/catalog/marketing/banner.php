<?php

use App\Http\Controllers\AdminApi\Catalog\Marketing\MarketingBannerController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'banners'], function () {
    Route::get('', [MarketingBannerController::class, 'index']);
    Route::post('', [MarketingBannerController::class, 'create']);
    Route::get('types', [MarketingBannerController::class, 'getTypes']);
    Route::group(['prefix' => '{marketing_banner_id}'], function () {
        Route::get('', [MarketingBannerController::class, 'get']);
        Route::put('', [MarketingBannerController::class, 'update']);
        Route::delete('', [MarketingBannerController::class, 'delete']);
    });
});
