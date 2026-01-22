<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('seller.chat.index', compact('users'));
    }

    public function startChatWithProduct(Product $product)
    {
        $buyerId = Auth::id();
        $sellerId = $product->shop->user_id ?? null;

        // 1. Validasi: Jangan chat diri sendiri
        if ($buyerId == $sellerId) {
            return back()->with('error', 'Anda tidak bisa mengirim pesan ke produk sendiri.');
        }

        // 2. Cari Room Chat (Cek apakah Buyer & Seller ini pernah ngobrol sebelumnya?)
        // Kita ABAIKAN product_id di conversation, karena 1 user = 1 room.
        $conversation = Conversation::where(function ($q) use ($buyerId, $sellerId) {
            $q->where('sender_id', $buyerId)->where('receiver_id', $sellerId);
        })
            ->orWhere(function ($q) use ($buyerId, $sellerId) {
                $q->where('sender_id', $sellerId)->where('receiver_id', $buyerId);
            })
            ->first();

        // 3. Jika belum ada room, buat baru
        if (!$conversation) {
            $conversation = Conversation::create([
                'sender_id' => $buyerId,
                'receiver_id' => $sellerId,
                'last_message_at' => now(),
            ]);
        }

        // 4. === MAGIC NYA DISINI (GAYA 2) ===
        // Buat Pesan Otomatis berisi Lampiran Produk

        // Cek agar tidak double kirim jika user refresh page atau klik berkali-kali dalam waktu singkat
        // (Logic: Cek pesan terakhir di room ini, apakah product_id nya sama?)
        $lastMessage = Message::where('conversation_id', $conversation->id)
            ->latest()
            ->first();

        if (!$lastMessage || $lastMessage->product_id != $product->id) {
            Message::create([
                'conversation_id' => $conversation->id,
                'user_id' => $buyerId, // Pengirimnya adalah Buyer yang sedang login
                'body' => 'Halo kak, saya tertarik dengan produk ini. Apakah stok masih ada?', // Teks default
                'product_id' => $product->id, // Lampirkan ID Produk
                'is_read' => false,
            ]);

            // Update waktu chat agar naik ke paling atas di sidebar seller
            $conversation->update(['last_message_at' => now()]);
        }

        // 5. Redirect User ke Halaman Chat
        return redirect()->route('chat.buyer', ['conversation_id' => $conversation->id]);
    }
}
