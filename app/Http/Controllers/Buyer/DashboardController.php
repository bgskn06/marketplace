<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where('name', 'like', "%{$q}%")->orWhere('description', 'like', "%{$q}%");
        }
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('name', $request->input('category'));
            });
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

        // Sidebar: Kategori
        $categories = \App\Models\Category::orderBy('name')->get();

        // Sidebar: Ringkasan Keranjang

        $cartSummary = [
            'count' => Auth::check() ? Auth::user()->cartItems()->count() : 0,
            'total' => Auth::check() ? Auth::user()->cartItems()->with('product')->get()->sum(function ($item) {
                return $item->product ? $item->product->price * $item->quantity : 0;
            }) : 0,
        ];

        // Sidebar: Pesanan Terbaru
        $recentOrders = Auth::check() ? Auth::user()->orders()->latest()->take(2)->get() : collect();

        // Sidebar: Pesan Terbaru
        $recentMessages = Auth::check() ? \App\Models\Message::where('user_id', Auth::id())->latest()->take(2)->get() : collect();

        // Sidebar: Penjual Direkomendasikan
        $recommendedSellers = \App\Models\Shop::with(['products' => function ($q) {
            $q->select('shop_id', 'rating');
        }])->get()->map(function ($shop) {
            $avgRating = $shop->products->count() > 0 ? $shop->products->avg('rating') : null;
            $shop->rating = $avgRating ?? 0;
            return $shop;
        })->sortByDesc('rating')->take(3);

        $summary = [
            'cart_count' => $cartSummary['count'],
            'orders_count' => Auth::check() ? Auth::user()->orders()->count() : 0,
        ];

        $stats = [
            'product_count' => Product::count(),
            'top_rated_count' => Schema::hasColumn('products', 'rating') ? \App\Models\Product::where('rating', '>=', 4.5)->count() : 0,
            'shops_count' => \App\Models\Shop::count(),
        ];

        return view('buyer.dashboard', compact('summary', 'products', 'stats', 'categories', 'cartSummary', 'recentOrders', 'recentMessages', 'recommendedSellers'));
    }
}
