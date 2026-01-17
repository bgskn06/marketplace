<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Seller\ShopController;
use App\Http\Controllers\Buyer\DashboardController;
use App\Http\Controllers\Buyer\CartController;
use App\Http\Controllers\Buyer\PagesController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view('dashboard');
});

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

        Route::post('/users/{user}/reject', [UserManagementController::class, 'reject'])
            ->name('users.reject');

        Route::resource('categories', CategoryController::class);
    });

// Admin: Seller Requests
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/seller-requests', [App\Http\Controllers\Admin\SellerRequestController::class, 'index'])->name('admin.seller-requests.index');
    Route::post('/seller-requests/{id}/approve', [App\Http\Controllers\Admin\SellerRequestController::class, 'approve'])->name('admin.seller-requests.approve');
    Route::post('/seller-requests/{id}/reject', [App\Http\Controllers\Admin\SellerRequestController::class, 'reject'])->name('admin.seller-requests.reject');
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
    Route::patch('/buyer/cart/{item}', [App\Http\Controllers\Buyer\CartController::class, 'update'])->name('buyer.cart.update');
    Route::delete('/buyer/cart/{item}', [App\Http\Controllers\Buyer\CartController::class, 'remove'])->name('buyer.cart.remove');
    Route::get('/buyer/cart/checkout', [App\Http\Controllers\Buyer\CartController::class, 'showCheckout'])->name('buyer.cart.checkout.show');
    Route::post('/buyer/cart/checkout', [App\Http\Controllers\Buyer\CartController::class, 'checkout'])->name('buyer.cart.checkout');
    Route::post('/cart/update/{item}', [CartController::class, 'update']);
    Route::post('/cart/remove/{item}', [CartController::class, 'remove']);
});

// Buyer: Form & proses daftar seller
Route::middleware(['auth'])->group(function () {
    Route::get('/buyer/register-seller', function () {
        return view('Buyer.register-seller');
    })->name('seller.register');
    Route::post('/buyer/register-seller', [App\Http\Controllers\Buyer\RegisterSellerController::class, 'store'])->name('buyer.seller.register');
});

Route::middleware('auth')->get('/dashboard', function () {
    return match (Auth::user()->role) {
        'admin'  => redirect()->route('admin.dashboard.index'),
        'seller' => redirect()->route('seller.dashboard.index'),
        default  => redirect()->route('buyer.dashboard'),
    };
})->name('dashboard');

require __DIR__ . '/auth.php';
