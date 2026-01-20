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

        // Allow authenticated users to leave a shop review immediately (order_id optional)
        $lastOrderId = \App\Models\Order::where('user_id', Auth::id())
            ->whereHas('orderItems.product', function ($q) use ($shop) {
                $q->where('shop_id', $shop->id);
            })->orderBy('created_at', 'desc')->value('id');

        $existing = ShopReview::where('user_id', Auth::id())->where('shop_id', $shop->id)->first();
        if ($existing) {
            $existing->update([
                'rating' => $request->input('rating'),
                'comment' => $request->input('comment'),
                'order_id' => $lastOrderId,
            ]);
        } else {
            ShopReview::create([
                'user_id' => Auth::id(),
                'shop_id' => $shop->id,
                'rating' => $request->input('rating'),
                'comment' => $request->input('comment'),
                'order_id' => $lastOrderId,
            ]);
        }

        return redirect()->route('shops.show', $shop)->with('success', 'Terima kasih, ulasan Anda telah disimpan.');
    }
}
