<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Seller\ShopController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    return match (Auth::user()->role) {
        'admin'  => redirect()->route('admin.dashboard.index'),
        'seller' => redirect()->route('seller.dashboard.index'),
        default  => redirect()->route('dashboard'),
    };
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', fn() => view('admin.dashboard.index'))
            ->name('dashboard.index');

        Route::get('/users', [UserManagementController::class, 'index'])
            ->name('users.index');

        Route::post('/users/{user}/promote', [UserManagementController::class, 'promote'])
            ->name('users.promote');

        Route::post('/users/{user}/demote', [UserManagementController::class, 'demote'])
            ->name('users.demote');

        Route::resource('categories', CategoryController::class);
    });

Route::middleware(['auth', 'role:seller'])
    ->prefix('seller')
    ->name('seller.')
    ->group(function () {

        Route::get('/dashboard', function () {
            if (!Auth::user()->shop) {
                return redirect()->route('seller.shop.create');
            }

            return view('seller.dashboard.index');
        })->name('dashboard.index');

        Route::get('/shop/create', [ShopController::class, 'create'])
            ->name('shop.create');

        Route::post('/shop', [ShopController::class, 'store'])
            ->name('shop.store');

        Route::middleware('seller.shop')->group(function () {
            Route::resource('products', ProductController::class);
        });
    });




require __DIR__ . '/auth.php';
