<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Conversation;
use App\Models\Message;
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
    }

    public function sendMessage()
    {
        $this->validate(['body' => 'required|string']);

        Message::create([
            'conversation_id' => $this->selectedConversation->id,
            'user_id' => Auth::id(),
            'body' => $this->body
        ]);

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
                ->with('user')
                ->get();
        }

        return view('livewire.seller-chat', [
            'messages' => $messages
        ]);
    }
}
