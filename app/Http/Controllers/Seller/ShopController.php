<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function create()
    {
        return view('seller.shop.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'logo' => 'nullable|image',
        ]);

        // dd($validated);

        Shop::create([
            'user_id'   => Auth::id(),
            'name' => $validated['name'],
            'address'    => $validated['address'] ?? null,
            'logo'      => null,
        ]);

        return redirect()
            ->route('seller.dashboard.index')
            ->with('success', 'Toko berhasil dibuat');
    }
}
