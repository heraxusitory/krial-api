<?php

use App\Http\Controllers\AdminApi\Catalog\DG\DgProductController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'products'], function () {
    Route::get('', [DgProductController::class, 'index']);
});
