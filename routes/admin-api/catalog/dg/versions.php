<?php

use App\Http\Controllers\AdminApi\Catalog\DG\DgVersionController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'versions'], function () {
    Route::get('', [DgVersionController::class, 'index']);

});
