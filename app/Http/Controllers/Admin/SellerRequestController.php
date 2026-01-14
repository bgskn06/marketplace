<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SellerRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SellerRequestController extends Controller
{
    public function index()
    {
        $requests = SellerRequest::with('user')->latest()->paginate(20);
        return view('admin.seller-requests.index', compact('requests'));
    }

    public function approve($id)
    {
        $request = SellerRequest::findOrFail($id);
        $request->status = 'approved';
        $request->save();
        // Jadikan user seller
        $user = $request->user;
        $user->role = 'seller';
        $user->save();
        // (Opsional) Kirim email ke user
        // Mail::to($user->email)->send(new SellerApprovedMail($user));
        return back()->with('success', 'Permohonan seller disetujui.');
    }

    public function reject($id)
    {
        $request = SellerRequest::findOrFail($id);
        $request->status = 'rejected';
        $request->save();
        // (Opsional) Kirim email ke user
        // Mail::to($request->user->email)->send(new SellerRejectedMail($request->user));
        return back()->with('success', 'Permohonan seller ditolak.');
    }
}
