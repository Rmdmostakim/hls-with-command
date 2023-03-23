<?php

use App\Http\Controllers\FilesystemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::post('store', [FilesystemController::class, 'store']);
Route::post('check', function (Request $request) {
    $file = $request->path;
    // return $filePath = public_path(parse_url($file, PHP_URL_PATH));
    $filePath = Str::replace(env('APP_URL') . '/', '', $file);
    return $result = File::exists($filePath);
});
Route::get('watch/{playlist}', [FilesystemController::class, 'watch']);
Route::get('/{file}', [FilesystemController::class, 'accessFile']);
