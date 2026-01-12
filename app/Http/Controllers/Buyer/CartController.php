<?php

namespace App\Http\Controllers\Buyer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Product;

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
        return response()->json(['success' => true, 'cart_count' => $count]);
    }

    public function remove(Request $request, CartItem $item)
    {
        $user = $request->user();
        if ($item->user_id !== $user->id) {
            return response()->json(['success' => false], 403);
        }
        $item->delete();
        return response()->json(['success' => true, 'cart_count' => $user->cartItems()->count()]);
    }
}
