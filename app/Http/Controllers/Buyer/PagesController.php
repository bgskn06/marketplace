<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function orders(Request $request)
    {
        $user = auth()->user();
        if (! $user) {
            return view('buyer.orders', ['orders' => collect(), 'counts' => [], 'status' => null]);
        }

        // base query
        $ordersQuery = $user->orders()->with(['orderItems.product']);

        // counts per status for badges
        $counts = $user->orders()->select('status', \Illuminate\Support\Facades\DB::raw('count(*) as c'))
            ->groupBy('status')
            ->pluck('c', 'status')
            ->toArray();

        // filter by status via query param ?status=2
        $status = $request->query('status');
        if ($status !== null && $status !== '') {
            $ordersQuery->where('status', $status);
        }

        $orders = $ordersQuery->latest()->get();

        return view('buyer.orders', compact('orders', 'counts', 'status'));
    }
    public function messages()
    {
        $user = auth()->user();
        $messages = $user ? $user->messages()->latest()->get() : collect();
        return view('buyer.messages', compact('messages'));
    }

    public function cart()
    {
        $user = auth()->user();
        $cart = $user ? $user->cartItems()->with('product')->get() : collect();
        return view('buyer.cart', compact('cart'));
    }

    public function profile()
    {
        $user = auth()->user();

        return view('buyer.profile', compact('user'));
    }
}
