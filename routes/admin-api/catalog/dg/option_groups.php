<?php

use App\Http\Controllers\AdminApi\Catalog\DG\DgOptionGroupController;

Route::group(['prefix' => 'option_groups'], function () {
    Route::get('', [DgOptionGroupController::class, 'index']);
});
