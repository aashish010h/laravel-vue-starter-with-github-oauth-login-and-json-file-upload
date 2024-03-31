<?php

use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\File\FileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::post('uploadFile', [FileController::class, 'store']);
    Route::get('files', [FileController::class, 'index']);
    Route::get('auth/github/logout', [SocialiteController::class, 'logout']);
});

Route::get('getToken', [SocialiteController::class, 'generateToken']);
