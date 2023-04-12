<?php

use App\Http\Controllers\LearnController;
use Illuminate\Support\Facades\Route;

Route::post('create', [LearnController::class, 'createLearn']);
Route::group(['prefix' => 'group', 'as' => 'group'], function () {
    Route::post('learn', [LearnController::class, 'learn']);
});
