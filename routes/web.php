<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CvController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

Route::get('cv/{cv}/foto', [CvController::class, 'foto'])->name('cv.foto');
Route::resource('cv', CvController::class);
