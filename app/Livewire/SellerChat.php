<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class SellerChat extends Component
{
    public Collection $conversations;
    public $selectedConversation;
    public $body = '';

    // 1. Function Baru untuk Load Sidebar
    public function loadConversations()
    {
        $this->conversations = Conversation::where('sender_id', Auth::id())
            ->orWhere('receiver_id', Auth::id())
            ->orderByDesc('last_message_at')
            ->withCount(['unreadMessages'])
            ->with(['sender', 'receiver', 'latestMessage'])
            ->get();
    }

    public function mount()
    {
        // Panggil function load tadi
        $this->loadConversations();

        // Auto-select logic (hanya dijalankan saat halaman pertama dibuka)
        if ($this->conversations->isNotEmpty()) {
            $this->selectedConversation = $this->conversations->first();
        } else {
            $this->conversations = new Collection();
        }
    }

    public function selectConversation($conversationId)
    {
        $this->selectedConversation = Conversation::findOrFail($conversationId);

        // === LOGIC BARU: TANDAI SUDAH DIBACA ===
        // Update pesan yang ada di room ini, yang BUKAN punya saya, jadi is_read = 1
        Message::where('conversation_id', $this->selectedConversation->id)
            ->where('user_id', '!=', Auth::id())
            ->update(['is_read' => true]);

        // Refresh Sidebar (Supaya badge merahnya hilang setelah diklik)
        $this->loadConversations();

        $this->dispatch('chat-updated');
    }

    public function sendMessage()
    {
        $this->validate(['body' => 'required|string']);

        try {
            $message = Message::create([
                'conversation_id' => $this->selectedConversation->id,
                'user_id' => Auth::id(),
                'body' => $this->body
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Fallback for DBs where `id` isn't AUTO_INCREMENT (temporary fix)
            if (str_contains($e->getMessage(), "Field 'id' doesn't have a default value")) {
                try {
                    $nextId = \Illuminate\Support\Facades\DB::table('messages')->max('id');
                    $nextId = $nextId ? $nextId + 1 : 1;

                    \Illuminate\Support\Facades\DB::table('messages')->insert([
                        'id' => $nextId,
                        'conversation_id' => $this->selectedConversation->id,
                        'user_id' => Auth::id(),
                        'body' => $this->body,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $message = Message::find($nextId);
                } catch (\Exception $e2) {
                    throw $e2; // bubble up if fallback fails
                }
            } else {
                throw $e; // rethrow if different DB error
            }
        }

        $this->selectedConversation->update(['last_message_at' => now()]);

        // Reset text input di sisi server
        $this->reset('body');

        // Refresh sidebar
        $this->loadConversations();

        // === TAMBAHKAN BARIS PENTING INI ===
        // Memberi sinyal ke JavaScript: "Hei, pesan sudah terkirim! Scroll ke bawah & bersihkan input."
        $this->dispatch('chat-updated');
    }

    public function deleteMessage($messageId)
    {
        $message = Message::find($messageId);

        if ($message && $message->user_id == Auth::id()) {

            $message->delete();

            $this->loadConversations();

            // 4. Kirim notifikasi sukses (jika punya library notif/toaster)
            // $this->dispatch('notify', 'Pesan dihapus');
            $this->dispatch('message-deleted');
        }
    }

    // Tambahkan properti baru
    public $searchKeyword = '';
    public $searchResults = [];
    public $selectedProductPreview = null; // Untuk menampilkan produk yang sudah dipilih

    // Method yang jalan otomatis saat user mengetik (Real-time)
    public function updatedSearchKeyword()
    {
        // Jika keyword kosong, kosongkan hasil
        if (strlen($this->searchKeyword) < 2) {
            $this->searchResults = [];
            return;
        }

        // Cari produk (Limit 5 saja biar ringan)
        // Sesuaikan 'store_id' dengan logic toko Anda jika ada multi-vendor
        $this->searchResults = \App\Models\Product::where('name', 'like', '%' . $this->searchKeyword . '%')
            ->latest()
            ->take(5)
            ->get();
    }

    // Method saat produk di daftar diklik
    public function selectProductForNego($productId)
    {
        $product = \App\Models\Product::find($productId);

        if ($product) {
            $this->negotiationProductId = $product->id; // Masukkan ke variable utama form
            $this->selectedProductPreview = $product;   // Simpan object untuk preview tampilan

            // Reset pencarian
            $this->searchKeyword = '';
            $this->searchResults = [];
        }
    }

    // Method untuk membatalkan pilihan (Ganti produk)
    public function removeSelectedProduct()
    {
        $this->negotiationProductId = null;
        $this->selectedProductPreview = null;
    }

    // PROPERTI BARU
    public $offerAmount;
    public $negotiationProductId;

    // METHOD UNTUK MENGIRIM PESAN NEGO
    public function sendOfferMessage()
    {
        // 1. Validasi Input
        $this->validate([
            'offerAmount' => 'required|numeric|min:100',
            'negotiationProductId' => 'required|exists:products,id',
        ]);

        // 2. Buat Pesan Nego
        Message::create([
            'conversation_id' => $this->selectedConversation->id,
            'user_id' => Auth::id(),
            'product_id' => $this->negotiationProductId, // Produk yang ditawar
            'body' => 'Saya menawarkan harga baru.', // Pesan pengantar (opsional)
            'type' => 'negotiation', // TIPE PENTING
            'offer_price' => $this->offerAmount,
            'offer_status' => 0 // 0 = Pending
        ]);

        // 3. Reset Input
        $this->offerAmount = null;
        $this->negotiationProductId = null;

        // 4. Refresh Chat
        $this->dispatch('chat-updated');
    }

    public function acceptOffer($messageId)
    {
        $message = Message::find($messageId);

        // Validasi User
        // if ($message && $message->conversation->sender_id != Auth::id() && $message->conversation->receiver_id != Auth::id()) {
        //     return;
        // }

        // UPDATE STATUS JADI 1 (Accepted)
        $message->update(['offer_status' => 1]);

        // Kirim notifikasi otomatis
        Message::create([
            'conversation_id' => $message->conversation_id,
            'user_id' => Auth::id(),
            'body' => "✅ Tawaran diterima! Harga produk diubah menjadi Rp " . number_format($message->offer_price),
            'type' => 'text'
        ]);

        $this->dispatch('chat-updated');
    }

    public function rejectOffer($messageId)
    {
        $message = Message::find($messageId);

        if ($message) {
            // UPDATE STATUS JADI 2 (Rejected)
            $message->update(['offer_status' => 2]);
        }

        $this->dispatch('chat-updated');
    }

    // public function startChatWithProduct(Product $product)
    // {
    //     $userId = Auth::id();
    //     $sellerId = $product->user_id;

    //     if ($userId == $sellerId) {
    //         return back()->with('error', 'Tidak bisa chat produk sendiri');
    //     }

    //     $conversation = Conversation::where(function ($q) use ($userId, $sellerId) {
    //         $q->where('sender_id', $userId)->where('receiver_id', $sellerId);
    //     })
    //         ->orWhere(function ($q) use ($userId, $sellerId) {
    //             $q->where('sender_id', $sellerId)->where('receiver_id', $userId);
    //         })
    //         ->first();

    //     if (!$conversation) {
    //         $conversation = Conversation::create([
    //             'sender_id' => $userId,
    //             'receiver_id' => $sellerId,
    //             'last_message_at' => now(),
    //         ]);
    //     }

    //     Message::create([
    //         'conversation_id' => $conversation->id,
    //         'user_id' => $userId,
    //         'body' => 'Saya tertarik dengan produk ini : ',
    //         'product_id' => $product->id,
    //         'is_read' => false,
    //     ]);

    //     // Update timestamp conversation
    //     $conversation->update(['last_message_at' => now()]);

    //     // 4. Redirect ke Chat Room
    //     return redirect()->route('chat.index', ['conversation_id' => $conversation->id]);
    // }

    public function render()
    {
        // 1. === LOGIC TAMBAHAN: AUTO READ SAAT CHAT TERBUKA ===
        if ($this->selectedConversation) {
            // Cek apakah ada pesan baru dari lawan bicara di room yang sedang aktif
            Message::where('conversation_id', $this->selectedConversation->id)
                ->where('user_id', '!=', Auth::id())
                ->where('is_read', false) // Hanya update yang belum dibaca
                ->update(['is_read' => true]);

            // PENTING: Refresh data sidebar setelah update status
            // Agar badge merah langsung hilang/tidak sempat muncul
            $this->loadConversations();
        }

        // 2. Ambil Pesan (Logic lama)
        $messages = [];
        if ($this->selectedConversation) {
            $messages = Message::where('conversation_id', $this->selectedConversation->id)
                ->with(['user', 'product', 'product.mainPhoto'])
                ->get();
        }

        return view('livewire.seller-chat', [
            'messages' => $messages
        ]);
    }
}
