<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilesystemController;


Route::post('store', [FilesystemController::class, 'store']);
Route::get('watch/{playlist}', [FilesystemController::class, 'watch']);
Route::get('/{file}', [FilesystemController::class, 'accessFile']);
