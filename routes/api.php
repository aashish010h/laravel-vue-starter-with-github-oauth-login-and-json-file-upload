<?php

use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\File\FileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//protected route for the authenticated users only
Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    // route to handle file upload
    Route::post('uploadFile', [FileController::class, 'store']);

    // route to fetch files
    Route::get('files', [FileController::class, 'index']);

    // route to logout from GitHub OAuth
    Route::get('auth/github/logout', [SocialiteController::class, 'logout']);
});

//generate sanstum access token for frontend api call
Route::get('getToken', [SocialiteController::class, 'generateToken']);
