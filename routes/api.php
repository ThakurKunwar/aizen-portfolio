<?php

use App\Http\Controllers\Api\V1\IndexController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\ProjectController;
use Illuminate\Support\Facades\Route;

// Public, read-only JSON. The console on the home page calls exactly these routes.
Route::prefix('v1')->name('api.v1.')->middleware('throttle:api')->group(function () {
    Route::get('/', IndexController::class)->name('index');

    Route::get('about', [ProfileController::class, 'about'])->name('about');
    Route::get('skills', [ProfileController::class, 'skills'])->name('skills');
    Route::get('experience', [ProfileController::class, 'experience'])->name('experience');
    Route::get('contact', [ProfileController::class, 'contact'])->name('contact');
    Route::get('life', [ProfileController::class, 'life'])->name('life');

    Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('projects/{slug}', [ProjectController::class, 'show'])->name('projects.show');
});
