<?php

use App\Http\Controllers\FeedController;
use Illuminate\Support\Facades\Route;

Route::post('gcat/store', [FeedController::class, 'storeGcat']);
Route::post('pcat/store', [FeedController::class, 'storePcat']);
// Route::post('create', [FeedController::class, 'createFeed']);
Route::group(['prefix' => 'merchant', 'as' => 'merchant'], function () {
    Route::post('create', [FeedController::class, 'createFeed']);
});
