<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share a safe URL for buyer orders across all views. Use a hardcoded absolute URL to avoid named-route resolution during boot.
        View::share('buyerOrdersUrl', url('/buyer/orders'));
    }
}
