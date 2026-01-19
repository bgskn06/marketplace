<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class BuyerChat extends Component
{
    public Collection $conversations;
    public $selectedConversation;
    public $body = '';

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
        $this->loadConversations();

        if ($this->conversations->isNotEmpty()) {
            $this->selectedConversation = $this->conversations->first();
        } else {
            $this->conversations = new Collection();
        }
    }

    public function selectConversation($conversationId)
    {
        $this->selectedConversation = Conversation::findOrFail($conversationId);

        Message::where('conversation_id', $this->selectedConversation->id)
            ->where('user_id', '!=', Auth::id())
            ->update(['is_read' => true]);

        $this->loadConversations();
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
                    throw $e2;
                }
            } else {
                throw $e;
            }
        }

        $this->selectedConversation->update(['last_message_at' => now()]);

        $this->reset('body');

        $this->loadConversations();

        $this->dispatch('chat-updated');
    }

    public function render()
    {
        if ($this->selectedConversation) {
            Message::where('conversation_id', $this->selectedConversation->id)
                ->where('user_id', '!=', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true]);

            $this->loadConversations();
        }

        $messages = [];
        if ($this->selectedConversation) {
            $messages = Message::where('conversation_id', $this->selectedConversation->id)
                ->with('user')
                ->get();
        }

        return view('livewire.buyer-chat', [
            'messages' => $messages
        ]);
    }
}
