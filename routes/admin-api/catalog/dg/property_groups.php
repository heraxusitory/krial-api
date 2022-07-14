<?php

use App\Http\Controllers\AdminApi\Catalog\DG\DgPropertyGroupController;

Route::group(['prefix' => 'property_groups'], function () {
    Route::get('', [DgPropertyGroupController::class, 'index']);
});
