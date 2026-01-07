<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', fn () => view('admin.dashboard'))
            ->name('dashboard');

        Route::get('/users', [UserManagementController::class, 'index'])
            ->name('users.index');

        // Route::post('/users/{user}/promote', [UserManagementController::class, 'promote'])
        //     ->name('users.promote');
});


Route::middleware(['auth', 'role:seller'])->group(function () {
    Route::get('/seller/dashboard', function () {
        return view('seller.dashboard');
    });
});


require __DIR__.'/auth.php';
