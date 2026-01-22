<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Product;

class SellerDashboard extends Component
{
    public function render()
    {
        $user = Auth::user();
        
        // --- 1. DATA TOKO & PRODUK ---
        // Kita cari dulu tokonya user ini
        $shop = $user->shop; 
        
        $totalProducts = 0;
        $shopRating = 0;

        // Jika user sudah punya toko
        if ($shop) {
            // Hitung produk berdasarkan shop_id
            $totalProducts = Product::where('shop_id', $shop->id)->count();
            
            // Hitung Rata-rata Rating Toko dari semua produk di toko ini
            // (Mengambil kolom 'rating' yang ada di model Product Anda)
            $shopRating = Product::where('shop_id', $shop->id)->avg('rating') ?? 0;
        }

        // --- 2. DATA ORDER (Tetap pakai seller_id / User ID) ---
        // Asumsi: Di tabel orders, 'seller_id' yang disimpan adalah ID User, bukan ID Shop.
        // (Sesuai dengan fitur order yang sebelumnya kita bahas)
        $revenue = Order::where('seller_id', $user->id)
            ->where('status', Order::STATUS_COMPLETED)
            ->sum('total');

        $toShip = Order::where('seller_id', $user->id)
            ->where('status', Order::STATUS_PAID) // Status 2
            ->count();

        // Data Statistik Order Pipeline
        $stats = [
            'new'       => Order::where('seller_id', $user->id)->where('status', Order::STATUS_UNPAID)->count(),
            'paid'      => $toShip,
            'shipped'   => Order::where('seller_id', $user->id)->where('status', Order::STATUS_SHIPPED)->count(),
            'completed' => Order::where('seller_id', $user->id)->where('status', Order::STATUS_COMPLETED)->count(),
            'cancelled' => Order::where('seller_id', $user->id)->where('status', Order::STATUS_CANCELLED)->count(),
        ];

        // 5 Transaksi Terakhir
        $recentOrders = Order::with('user')
            ->where('seller_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.seller-dashboard', [
            'revenue'       => $revenue,
            'toShip'        => $toShip,
            'totalProducts' => $totalProducts,
            'rating'        => $shopRating, // Sekarang ratingnya dinamis!
            'stats'         => $stats,
            'recentOrders'  => $recentOrders,
        ]);
    }
}