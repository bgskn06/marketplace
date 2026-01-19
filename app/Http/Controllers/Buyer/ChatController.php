<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // List conversations for current user
    public function index()
    {
        $userId = Auth::id();
        $conversations = Conversation::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['latestMessage', 'sender', 'receiver'])
            ->orderByDesc('last_message_at')
            ->get();

        return view('buyer.chat.index', compact('conversations'));
    }

    // Start or return existing conversation with given user
    public function start(User $user)
    {
        $me = Auth::user();
        if ($me->id === $user->id) {
            return redirect()->route('buyer.chat.index')->with('error', 'Tidak bisa memulai chat dengan diri sendiri.');
        }

        $conversation = Conversation::where(function ($q) use ($me, $user) {
            $q->where('sender_id', $me->id)->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($me, $user) {
            $q->where('sender_id', $user->id)->where('receiver_id', $me->id);
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'sender_id' => $me->id,
                'receiver_id' => $user->id,
                'last_message_at' => now(),
            ]);
        }

        return redirect()->route('buyer.chat.show', $conversation);
    }

    // Show a conversation
    public function show(Conversation $conversation)
    {
        $me = Auth::user();
        if ($conversation->sender_id !== $me->id && $conversation->receiver_id !== $me->id) {
            abort(403);
        }

        $messages = $conversation->messages()->with('user')->latest()->get()->reverse(); // oldest first

        return view('buyer.chat.show', compact('conversation', 'messages'));
    }

    public function sendMessage(Request $request, Conversation $conversation)
    {
        $me = Auth::user();
        if ($conversation->sender_id !== $me->id && $conversation->receiver_id !== $me->id) {
            abort(403);
        }

        $request->validate(['message' => 'required|string|max:2000']);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $me->id,
            'body' => $request->input('message'),
            'is_read' => false,
        ]);

        $conversation->last_message_at = now();
        $conversation->save();

        return redirect()->route('buyer.chat.show', $conversation)->with('success', 'Pesan terkirim.');
    }
}
