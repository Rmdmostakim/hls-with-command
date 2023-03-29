<?php

use App\Http\Controllers\FeedController;
use Illuminate\Support\Facades\Route;

Route::post('gcat/store', [FeedController::class, 'storeGcat']);
Route::post('pcat/store', [FeedController::class, 'storePcat']);
// Route::post('create', [FeedController::class, 'createFeed']);
Route::group(['prefix' => 'merchant', 'as' => 'merchant'], function () {
    Route::post('create', [FeedController::class, 'createFeed']);
    Route::get('search', [FeedController::class, 'getAllSearchItems']);
    Route::get('feed/get', [FeedController::class, 'getAllMerchantFeed']);
});

Route::get('get/all', [FeedController::class, 'getAllFeed']);
Route::get('gcat/get/all', [FeedController::class, 'getAllGcat']);
Route::get('pcat/get/all', [FeedController::class, 'getAllPcat']);

Route::post('view', [FeedController::class, 'increaseView']);
Route::post('share', [FeedController::class, 'increaseShare']);
Route::post('like/store', [FeedController::class, 'storeFeedLike']);
Route::post('comment', [FeedController::class, 'storeFeedComment']);

Route::post('comment/update', [FeedController::class, 'updateFeedComment']);
Route::post('comment/delete', [FeedController::class, 'deleteFeedComment']);
