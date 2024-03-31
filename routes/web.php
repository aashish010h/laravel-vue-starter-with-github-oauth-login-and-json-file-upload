<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\File\FileController;

Route::get('/', function () {
    return view('auth.login');
});

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');


// routes for SocialiteController using controller method
Route::controller(SocialiteController::class)->group(function () {

    // route to redirect to GitHub for authentication
    Route::get('auth/github', 'redirectToGithub')->name('auth.github');

    // route to handle GitHub callback after authentication
    Route::get('auth/github/callback', 'handlegithubCallback');
});

// route to export a file as Excel
Route::get('/export/{fileId}', [FileController::class, "exportAsExcel"]);

// fallback route to handle all other routes and return the welcome view , uses vue router for others routes excpeted defined in web.php
Route::get('{any}', function () {
    return view('welcome');
})->where('any', '.*');
