<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1'
        ]);
        $user = $request->user();
        $product = Product::findOrFail($request->product_id);
        $item = CartItem::firstOrCreate([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ], [
            'quantity' => $request->input('quantity', 1)
        ]);
        if ($request->filled('quantity') && $item->quantity != $request->quantity) {
            $item->quantity = $request->quantity;
            $item->save();
        }
        if (!$request->filled('quantity') && $item->wasRecentlyCreated === false) {
            $item->increment('quantity', 1);
        }
        $count = $user->cartItems()->count();
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'cart_count' => $count]);
        }

        return redirect()->back()->with('success', 'Berhasil dimasukkan ke keranjang');
    }

    public function remove(Request $request, CartItem $item)
    {
        $user = $request->user();
        if ($item->user_id !== $user->id) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false], 403);
            }
            return redirect()->back()->with('success', 'Gagal menghapus item');
        }

        $item->delete();

        $cartItems = $user->cartItems()->with('product')->get();
        $subtotal = $cartItems->sum(function ($i) {
            $p = $i->product;
            return ($p->price ?? $p->harga ?? 0) * $i->quantity;
        });

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'cart_count' => $user->cartItems()->count(), 'cart_subtotal' => $subtotal]);
        }

        return redirect()->back()->with('success', 'Item berhasil dihapus dari keranjang');
    }

    public function update(Request $request, CartItem $item)
    {
        $user = $request->user();
        if ($item->user_id !== $user->id) {
            return response()->json(['success' => false], 403);
        }

        $request->validate(['quantity' => 'required|integer|min:1']);

        $product = $item->product;
        $desired = (int) $request->input('quantity');

        if ($desired > ($product->stock ?? 0)) {
            return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi', 'stock' => $product->stock], 422);
        }

        $item->quantity = $desired;
        $item->save();

        $item_total = ($product->price ?? $product->harga ?? 0) * $item->quantity;
        $cartItems = $user->cartItems()->with('product')->get();
        $subtotal = $cartItems->sum(function ($i) {
            $p = $i->product;
            return ($p->price ?? $p->harga ?? 0) * $i->quantity;
        });

        return response()->json([
            'success' => true,
            'item_quantity' => $item->quantity,
            'item_total' => $item_total,
            'cart_subtotal' => $subtotal,
            'cart_count' => $user->cartItems()->count(),
        ]);
    }

    // Show checkout page with items and shipping options
    public function showCheckout(Request $request)
    {
        $user = $request->user();
        $items = $user->cartItems()->with('product')->get();
        $subtotal = $items->sum(function ($i) {
            $p = $i->product;
            return ($p->price ?? $p->harga ?? 0) * $i->quantity;
        });

        // simple shipping options (id => [label, price])
        $shippingOptions = [
            'standard' => ['Standard (3-5 hari)', 10000],
            'express' => ['Express (1-2 hari)', 25000],
        ];

        // Add payment options (COD and Bank Transfer)
        $paymentOptions = [
            'cod' => 'COD (Bayar di tempat)',
            'bank_transfer' => 'Transfer Bank (Bank Transfer)'
        ];

        return view('Buyer.checkout', compact('items', 'subtotal', 'shippingOptions', 'paymentOptions'));
    }

    public function checkout(Request $request)
    {
        $user = $request->user();
        $items = $user->cartItems()->with('product')->get();

        if ($items->isEmpty()) {
            if ($request->expectsJson()) return response()->json(['success' => false, 'message' => 'Keranjang kosong'], 422);
            return redirect()->route('buyer.cart')->with('success', 'Keranjang kosong');
        }

        $sellerId = null;
        $firstItem = $items->first();
        if ($firstItem && $firstItem->product && $firstItem->product->shop) {
            $sellerId = $firstItem->product->shop->user_id;
        }

        // validate stock
        foreach ($items as $item) {
            $p = $item->product;
            if ($item->quantity > ($p->stock ?? 0)) {
                if ($request->expectsJson()) return response()->json(['success' => false, 'message' => "Stok tidak mencukupi untuk: {$p->name}"], 422);
                return redirect()->back()->with('success', "Stok tidak mencukupi untuk: {$p->name}");
            }
        }

        // validate checkout form (payment method is hardcoded to 'cod')
        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'address' => 'required|string',
            'seller_id' => 'nullable|exists:users,id',
            'note' => 'nullable|string',
            'shipping' => 'nullable|string|in:standard,express',
        ]);

        // server-side shipping options (same as showCheckout)
        $shippingRates = [
            'standard' => 10000,
            'express' => 25000,
        ];
        $shippingKey = $request->input('shipping');
        $shippingPrice = $shippingRates[$shippingKey] ?? 0;

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $total = 0;
            foreach ($items as $item) {
                $p = $item->product;
                $total += ($p->price ?? $p->harga ?? 0) * $item->quantity;
            }

            // include shipping price
            $totalWithShipping = $total + $shippingPrice;

            $orderData = [
                'user_id' => $user->id,
                'order_number' => \Illuminate\Support\Str::upper(uniqid('ORD-')),
                'total' => $totalWithShipping,
                'seller_id' => $sellerId,
                // Use numeric status constant (default Unpaid)
                'status' => \App\Models\Order::STATUS_UNPAID,
            ];

            if (Schema::hasColumn('orders', 'recipient_name')) {
                $orderData['recipient_name'] = $request->input('recipient_name');
            }
            if (Schema::hasColumn('orders', 'shipping_address')) {
                $orderData['shipping_address'] = $request->input('address');
            }
            if (Schema::hasColumn('orders', 'shipping_method')) {
                $orderData['shipping_method'] = $shippingKey;
            }
            if (Schema::hasColumn('orders', 'shipping_price')) {
                $orderData['shipping_price'] = $shippingPrice;
            }
            if (Schema::hasColumn('orders', 'note')) {
                $orderData['note'] = $request->input('note');
            }

            // Payment method comes from request (default to 'cod')
            $paymentMethod = $request->input('payment_method', 'cod');
            if (! in_array($paymentMethod, ['cod', 'bank_transfer'])) {
                $paymentMethod = 'cod';
            }
            $orderData['payment_method'] = $paymentMethod;

            // If bank_transfer, generate a payment code and expiry (24 hours)
            if ($paymentMethod === 'bank_transfer') {
                $code = 'TRF-' . strtoupper(\Illuminate\Support\Str::random(8));
                $orderData['payment_code'] = $code;
                $orderData['payment_expires_at'] = now()->addDay();
            }

            $order = \App\Models\Order::create($orderData);

            // create order items, group items by seller so we can notify them later, decrement stock and clear cart
            $sellerGroups = [];
            foreach ($items as $item) {
                $p = $item->product;
                if ($p) {
                    // create order item snapshot
                    $order->orderItems()->create([
                        'product_id' => $p->id,
                        'quantity' => $item->quantity,
                        'price' => $p->price ?? $p->harga ?? 0,
                    ]);

                    $p->decrement('stock', $item->quantity);
                }

                $sellerUser = optional(optional($p)->shop)->user;
                if ($sellerUser) {
                    $sellerGroups[$sellerUser->id]['user'] = $sellerUser;
                    // store a simple snapshot of the purchased item for notification purposes
                    $sellerGroups[$sellerUser->id]['items'][] = [
                        'product_id' => $p->id ?? null,
                        'name' => $p->name ?? null,
                        'quantity' => $item->quantity,
                        'price' => $p->price ?? $p->harga ?? 0,
                    ];
                }

                // remove cart item after snapshotting
                $item->delete();
            }

            \Illuminate\Support\Facades\DB::commit();

            // notify each seller about their items in this order
            try {
                foreach ($sellerGroups as $group) {
                    $sellerUser = $group['user'] ?? null;
                    $sellerItems = $group['items'] ?? [];
                    if ($sellerUser) {
                        try {
                            $sellerUser->notify(new \App\Notifications\NewOrderNotification($order, $sellerItems));
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error('Failed to notify seller: ' . $e->getMessage());
                        }
                    }
                }
            } catch (\Exception $e) {
                // non-fatal: log and continue
                \Illuminate\Support\Facades\Log::error('Error while dispatching seller notifications: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            if ($request->expectsJson()) return response()->json(['success' => false, 'message' => 'Checkout gagal'], 500);
            return redirect()->back()->with('success', 'Checkout gagal');
        }

        // Determine redirect URL: payment page for bank_transfer, otherwise orders list
        $ordersUrl = url('/buyer/orders');
        $redirectUrl = $ordersUrl;
        if (isset($order) && $order->payment_method === 'bank_transfer') {
            $redirectUrl = route('buyer.orders.payment', $order);
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'order_id' => $order->id, 'redirect' => $redirectUrl]);
        }

        return redirect($redirectUrl)->with('success', 'Checkout berhasil. Order #' . $order->order_number);
    }
}
