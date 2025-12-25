<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SidebarController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/deleteavatar', [ProfileController::class, 'deleteAvatar'])->name('profile.deleteavatar');
    Route::post('/updateAvatar', [ProfileController::class, 'updateAvatar'])->name('profile.updateavatar');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//users
Route::middleware('auth')->group(function () {
    Route::middleware(['role:developer|admin'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulk-delete');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    });
});

//sidebars
Route::middleware('auth')->group(function () {
    Route::middleware(['role:developer'])->group(function () {
        Route::get('/sidebar', [SidebarController::class, 'index'])->name('sidebar.index');
        Route::post('/sidebar/reorder', [SidebarController::class, 'reorder'])->name('sidebar.reorder');
        Route::put('/sidebar/{id}', [SidebarController::class, 'update'])->name('sidebar.update');
        Route::post('/sidebar/new', [SidebarController::class, 'store'])->name('sidebar.store');
    });
});

require __DIR__ . '/auth.php';
