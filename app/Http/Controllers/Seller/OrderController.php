<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function confirmShip(Request $request, Order $order)
    {
        $seller = Auth::user();
        if (! $seller->shop) {
            return back()->with('error', 'Anda belum memiliki toko.');
        }

        $sellerItemsQuery = $order->orderItems()->whereHas('product', function ($q) use ($seller) {
            $q->where('shop_id', $seller->shop->id);
        })->whereNull('shipped_at');

        $sellerItems = $sellerItemsQuery->get();

        if ($sellerItems->isEmpty()) {
            return back()->with('error', 'Tidak ada item untuk dikirim di pesanan ini atau sudah dikirim.');
        }

        $request->validate([
            'tracking' => 'nullable|string|min:3'
        ]);

        $tracking = $request->input('tracking');

        foreach ($sellerItems as $item) {
            $item->tracking_number = $tracking;
            $item->shipped_at = now();
            $item->save();
        }

        // If all items in order are shipped now, update order status to SHIPPED
        $order->updateStatusAfterItemsShipped();

        return back()->with('success', 'Item toko Anda telah ditandai sebagai dikirim.');
    }
}
