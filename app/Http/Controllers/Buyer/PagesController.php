<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function orders()
    {
        $user = auth()->user();
        $orders = $user ? $user->orders()->latest()->get() : collect();
        return view('buyer.orders', compact('orders'));
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
