<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\ProjectImageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CvController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

// Public site
Route::get('/', HomeController::class)->name('home');
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{slug}', [ProjectController::class, 'show'])->name('projects.show');
Route::get('/cv', CvController::class)->name('cv');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:contact')
    ->name('contact.store');

// Admin (one account, created by the seeder; there is no sign-up page)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'create'])->name('login');
        Route::post('login', [AuthController::class, 'store'])->name('login.store');
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'destroy'])->name('logout');
        Route::redirect('/', '/admin/projects');

        Route::patch('projects/{project}/toggle/{field}', [AdminProjectController::class, 'toggle'])
            ->whereIn('field', ['published', 'featured'])
            ->name('projects.toggle');
        Route::delete('projects/{project}/images/{image}', [ProjectImageController::class, 'destroy'])
            ->scopeBindings()
            ->name('projects.images.destroy');
        Route::resource('projects', AdminProjectController::class)->except('show');

        Route::get('messages', [MessageController::class, 'index'])->name('messages.index');
        Route::delete('messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    });
});
