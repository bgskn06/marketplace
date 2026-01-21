<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function show(Shop $shop)
    {
        $products = $shop->products()->where('is_active', 1)->with(['mainPhoto'])->latest()->paginate(12);

        $avgRating = $shop->products()->whereNotNull('rating')->avg('rating');
        $shopReviewAvg = $shop->reviews()->avg('rating');

        // combine product rating avg and shop review avg if available
        if ($avgRating && $shopReviewAvg) {
            $shop->rating = round((($avgRating + $shopReviewAvg) / 2), 1);
        } elseif ($shopReviewAvg) {
            $shop->rating = round($shopReviewAvg, 1);
        } else {
            $shop->rating = $avgRating ? round($avgRating, 1) : 0;
        }

        $isFollowing = auth()->check() ? $shop->followers()->where('user_id', auth()->id())->exists() : false;

        $shopReviews = $shop->reviews()->with('user')->latest()->get();

        // can the current user review this shop? (allow immediate shop reviews without waiting for delivery)
        $canReview = auth()->check();

        return view('buyer.shop.show', compact('shop', 'products', 'isFollowing', 'shopReviews', 'canReview'));
    }
}
