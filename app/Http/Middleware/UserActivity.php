<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Pastikan import Model User
use Symfony\Component\HttpFoundation\Response;

class UserActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            User::where('id', Auth::id())->update(['last_seen' => now()]);
        }

        return $next($request);
    }
}