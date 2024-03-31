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

Route::controller(SocialiteController::class)->group(function () {
    Route::get('auth/github', 'redirectToGithub')->name('auth.github');
    Route::get('auth/github/callback', 'handlegithubCallback');
});

Route::get('/export/{fileId}', [FileController::class, "exportAsExcel"]);

Route::get('{any}', function () {
    return view('welcome');
})->where('any', '.*');
