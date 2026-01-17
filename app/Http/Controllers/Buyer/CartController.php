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

        return view('Buyer.checkout', compact('items', 'subtotal', 'shippingOptions'));
    }

    public function checkout(Request $request)
    {
        $user = $request->user();
        $items = $user->cartItems()->with('product')->get();

        if ($items->isEmpty()) {
            if ($request->expectsJson()) return response()->json(['success' => false, 'message' => 'Keranjang kosong'], 422);
            return redirect()->route('buyer.cart')->with('success', 'Keranjang kosong');
        }

        // validate stock
        foreach ($items as $item) {
            $p = $item->product;
            if ($item->quantity > ($p->stock ?? 0)) {
                if ($request->expectsJson()) return response()->json(['success' => false, 'message' => "Stok tidak mencukupi untuk: {$p->name}"], 422);
                return redirect()->back()->with('success', "Stok tidak mencukupi untuk: {$p->name}");
            }
        }

        // validate checkout form
        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'address' => 'required|string',
            'note' => 'nullable|string',
            'shipping' => 'nullable|string'
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
                'status' => 'pending',
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

            $order = \App\Models\Order::create($orderData);

            // group items by seller so we can notify them later, decrement stock and clear cart
            $sellerGroups = [];
            foreach ($items as $item) {
                $p = $item->product;
                if ($p) {
                    $p->decrement('stock', $item->quantity);
                }

                $sellerUser = optional(optional($p)->shop)->user;
                if ($sellerUser) {
                    $sellerGroups[$sellerUser->id]['user'] = $sellerUser;
                    $sellerGroups[$sellerUser->id]['items'][] = $item;
                }

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

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'order_id' => $order->id, 'redirect' => route('buyer.orders')]);
        }

        return redirect()->route('buyer.orders')->with('success', 'Checkout berhasil. Order #' . $order->order_number);
    }
}
