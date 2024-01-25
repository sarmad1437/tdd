<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

/*Route::post('login', LoginController::class)->name('login');
Route::post('register', RegisterController::class)->name('register');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::apiResource('posts', PostController::class);
});

Route::group(['prefix' => 'blog', 'as' => 'blog.'], function () {
    Route::get('posts',[BlogController::class,'index'])->name('posts');
});

Route::post('upload', UploadController::class)->name('upload');*/
