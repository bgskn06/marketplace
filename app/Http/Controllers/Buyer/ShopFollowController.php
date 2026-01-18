<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;
use App\Models\ShopFollow;

class ShopFollowController extends Controller
{
    public function store(Request $request, Shop $shop)
    {
        if (!Auth::check()) return redirect()->route('shops.show', $shop)->with('error', 'Anda harus login untuk mengikuti toko.');

        $existing = ShopFollow::where('user_id', Auth::id())->where('shop_id', $shop->id)->first();
        if (!$existing) {
            ShopFollow::create(['user_id' => Auth::id(), 'shop_id' => $shop->id]);
        }

        return redirect()->route('shops.show', $shop)->with('success', 'Anda sekarang mengikuti toko ini.');
    }

    public function destroy(Request $request, Shop $shop)
    {
        if (!Auth::check()) return redirect()->route('shops.show', $shop)->with('error', 'Anda harus login.');
        ShopFollow::where('user_id', Auth::id())->where('shop_id', $shop->id)->delete();
        return redirect()->route('shops.show', $shop)->with('success', 'Anda berhenti mengikuti toko ini.');
    }
}
