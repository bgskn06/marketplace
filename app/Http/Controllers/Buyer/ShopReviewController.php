<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;
use App\Models\ShopReview;

class ShopReviewController extends Controller
{
    public function store(Request $request, Shop $shop)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        if (!Auth::check()) {
            return redirect()->route('shops.show', $shop)->with('error', 'Anda harus login untuk memberi ulasan.');
        }

        // check if user has delivered order that contains any product from this shop
        $deliveredOrderWithShop = \App\Models\Order::where('user_id', Auth::id())
            ->where(function ($q) {
                $q->whereRaw("LOWER(`status`) = 'delivered'")->orWhereRaw("LOWER(`status`) = 'selesai'");
            })
            ->whereHas('orderItems.product', function ($q) use ($shop) {
                $q->where('shop_id', $shop->id);
            })->first();

        if (!$deliveredOrderWithShop) {
            return redirect()->route('shops.show', $shop)->with('error', 'Anda hanya bisa memberi ulasan setelah menerima pesanan dari toko ini.');
        }

        $existing = ShopReview::where('user_id', Auth::id())->where('shop_id', $shop->id)->first();
        if ($existing) {
            $existing->update([
                'rating' => $request->input('rating'),
                'comment' => $request->input('comment'),
                'order_id' => $deliveredOrderWithShop->id,
            ]);
        } else {
            ShopReview::create([
                'user_id' => Auth::id(),
                'shop_id' => $shop->id,
                'rating' => $request->input('rating'),
                'comment' => $request->input('comment'),
                'order_id' => $deliveredOrderWithShop->id,
            ]);
        }

        return redirect()->route('shops.show', $shop)->with('success', 'Terima kasih, ulasan Anda telah disimpan.');
    }
}
