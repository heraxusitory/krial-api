<?php

use App\Http\Controllers\AdminApi\Catalog\DG\DgOptionController;
use App\Http\Controllers\AdminApi\Catalog\DG\DgOptionGroupController;

Route::group(['prefix' => 'options'], function () {
    Route::get('', [DgOptionController::class, 'index']);
});
