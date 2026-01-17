<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\SellerRequest;
use App\Mail\SellerRequestNotification;

class RegisterSellerController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|string|max:255',
            'shop_address' => 'required|string|max:255',
            'shop_description' => 'nullable|string',
            'phone' => 'required|string|max:30',
        ]);

        // Simpan permohonan ke tabel seller_requests
        $sellerRequest = SellerRequest::create([
            'user_id' => Auth::id(),
            'shop_name' => $request->shop_name,
            'shop_address' => $request->shop_address,
            'shop_description' => $request->shop_description,
            'phone' => $request->phone,
            'status' => 'pending',
        ]);

        Mail::to(config('mail.admin_address', 'admin@example.com'))->send(new SellerRequestNotification($sellerRequest));

        Log::info('Permohonan Seller Baru (notifikasi admin)', [
            'user_id' => Auth::id(),
            'shop_name' => $request->shop_name,
        ]);

        return redirect()->route('seller.register')->with('success', 'Permohonan pendaftaran seller berhasil dikirim. Admin akan memproses permohonan Anda.');
    }
}
