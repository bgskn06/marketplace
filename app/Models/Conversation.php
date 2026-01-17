<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Conversation extends Model
{
    protected $guarded = [];
    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // RELASI UNTUK SIDEBAR: Ambil pesan terakhir
    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    // HELPER: Ambil data "Lawan Bicara" (Bukan Saya)
    public function getReceiverAttribute()
    {
        if ($this->sender_id == Auth::id()) {
            return $this->receiver()->first();
        }
        return $this->sender()->first();
    }

    public function unreadMessages()
    {
        return $this->hasMany(Message::class)
            ->where('is_read', false)
            ->where('user_id', '!=', Auth::id());
    }
}
