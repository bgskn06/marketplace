<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Product::query();
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where('title', 'like', "%{$q}%")->orWhere('description', 'like', "%{$q}%");
        }
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->input('max_price'));
        }
        if ($request->filled('sort')) {
            if ($request->input('sort') === 'price_asc') {
                $query->orderBy('price', 'asc');
            } elseif ($request->input('sort') === 'price_desc') {
                $query->orderBy('price', 'desc');
            }
        }
        $products = $query->with(['mainPhoto', 'photos'])->latest()->paginate(12);
        $summary = [
            'cart_count' => auth()->check() ? auth()->user()->cartItems()->count() : 0,
            'orders_count' => auth()->check() ? auth()->user()->orders()->count() : 0,
            'recommended_sellers' => [
                ['name' => 'Penjual A', 'rating' => 4.8],
                ['name' => 'Penjual B', 'rating' => 4.7],
            ],
        ];

        $stats = [
            'product_count' => \App\Models\Product::count(),
            'top_rated_count' => Schema::hasColumn('products', 'rating') ? \App\Models\Product::where('rating', '>=', 4.5)->count() : 0,
            'shops_count' => \App\Models\Shop::count(),
        ];

        return view('buyer.dashboard', compact('summary', 'products', 'stats'));
    }
}
