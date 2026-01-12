<?php

use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Seller\ShopController;
use App\Http\Controllers\Buyer\DashboardController;
use App\Http\Controllers\Buyer\CartController;
use App\Http\Controllers\Buyer\PagesController;
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

// Public product detail page for buyers
Route::get('/products/{product}', [App\Http\Controllers\Buyer\ProductController::class, 'show'])->name('products.show');

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

Route::middleware(['auth', 'role:buyer'])->group(function () {
    Route::get('/buyer/dashboard', [App\Http\Controllers\Buyer\DashboardController::class, 'index'])->name('buyer.dashboard');

    Route::get('/buyer/orders', [App\Http\Controllers\Buyer\PagesController::class, 'orders'])->name('buyer.orders');
    Route::get('/buyer/messages', [App\Http\Controllers\Buyer\PagesController::class, 'messages'])->name('buyer.messages');
    Route::get('/buyer/cart', [App\Http\Controllers\Buyer\PagesController::class, 'cart'])->name('buyer.cart');
    Route::get('/buyer/profile', [App\Http\Controllers\Buyer\PagesController::class, 'profile'])->name('buyer.profile');

    Route::get('/api/products', [App\Http\Controllers\Api\ProductController::class, 'index'])->name('api.products');

    Route::post('/buyer/cart/add', [App\Http\Controllers\Buyer\CartController::class, 'add'])->name('buyer.cart.add');
    Route::delete('/buyer/cart/{item}', [App\Http\Controllers\Buyer\CartController::class, 'remove'])->name('buyer.cart.remove');
});

Route::middleware('auth')->get('/dashboard', function () {
    return match (Auth::user()->role) {
        'admin'  => redirect()->route('admin.dashboard.index'),
        'seller' => redirect()->route('seller.dashboard.index'),
        default  => redirect()->route('buyer.dashboard'),
    };
})->name('dashboard');

require __DIR__ . '/auth.php';
