<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\ProductReview;

class ProductReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        if (!Auth::check()) {
            return redirect()->route('products.show', $product)->with('error', 'Anda harus login untuk memberi rating.');
        }

        // Check if user has at least one delivered order that contains this product
        $deliveredOrderWithProduct = \App\Models\Order::where('user_id', Auth::id())
            ->where(function ($q) {
                $q->whereRaw("LOWER(`status`) = 'delivered'")->orWhereRaw("LOWER(`status`) = 'selesai'");
            })
            ->whereHas('orderItems', function ($q) use ($product) {
                $q->where('product_id', $product->id);
            })->first();

        if (!$deliveredOrderWithProduct) {
            return redirect()->route('products.show', $product)->with('error', 'Anda hanya bisa memberi rating jika Anda menerima pesanan yang berisi produk ini.');
        }

        // Prevent multiple reviews per user-product
        $existing = ProductReview::where('user_id', Auth::id())->where('product_id', $product->id)->first();
        if ($existing) {
            // Update
            $existing->update([
                'rating' => $request->input('rating'),
                'comment' => $request->input('comment'),
                'order_id' => $deliveredOrderWithProduct->id ?? null,
            ]);
        } else {
            ProductReview::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'rating' => $request->input('rating'),
                'comment' => $request->input('comment'),
                'order_id' => $deliveredOrderWithProduct->id ?? null,
            ]);
        }

        // Recalculate product rating
        $product->recalcRating();

        return redirect()->route('products.show', $product)->with('success', 'Terima kasih, rating Anda telah disimpan.');
    }
}
