<div class="row">
    {{-- === KOLOM KIRI: DAFTAR CHAT === --}}
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Inbox</h6>
            </div>

            <div class="list-group list-group-flush" style="height: 500px; overflow-y: auto;">
                @forelse($conversations as $conv)
                    @php
                        $otherUser = $conv->sender_id == auth()->id() ? $conv->receiver : $conv->sender;
                        $isActive = $selectedConversation && $selectedConversation->id == $conv->id;
                    @endphp

                    <a href="javascript:void(0)" wire:click="selectConversation({{ $conv->id }})"
                        class="list-group-item list-group-item-action {{ $isActive ? 'active' : '' }}">

                        <div class="d-flex w-100 justify-content-between align-items-center">

                            {{-- Nama User & Badge Container --}}
                            <div class="d-flex align-items-center">
                                <h6 class="mb-1 {{ $isActive ? 'text-white' : 'text-dark' }} font-weight-bold">
                                    {{ $otherUser->name }}
                                </h6>

                                {{-- === BADGE UNREAD === --}}
                                {{-- Menggunakan unread_messages_count (Otomatis dari withCount di controller) --}}
                                @if ($conv->unread_messages_count > 0)
                                    <span class="ml-2 badge badge-error text-white badge-sm">
                                        {{ $conv->unread_messages_count }}
                                    </span>
                                @endif
                            </div>

                            <small class="{{ $isActive ? 'text-white' : 'text-muted' }}">
                                {{ $conv->last_message_at ? $conv->last_message_at->format('H:i') : '' }}
                            </small>
                        </div>

                        <small class="{{ $isActive ? 'text-white' : 'text-secondary' }} d-block text-truncate">
                            {{ $conv->latestMessage->body ?? 'Belum ada pesan' }}
                        </small>
                    </a>
                @empty
                    <div class="p-3 text-center text-muted">
                        Belum ada percakapan.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- === KOLOM KANAN: ROOM CHAT === --}}
    <div class="col-md-8 mb-4">
        <div class="card shadow-sm" style="height: 580px;"> {{-- Tinggi fix card --}}

            @if ($selectedConversation)
                @php
                    $chatPartner =
                        $selectedConversation->sender_id == auth()->id()
                            ? $selectedConversation->receiver
                            : $selectedConversation->sender;
                @endphp

                {{-- Header Chat Room --}}
                <div class="card-header bg-white d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">{{ $chatPartner->name }}</h6>
                    @if ($chatPartner->isOnline())
                        <span class="text-success">Online</span>
                    @else
                        Last seen {{ $chatPartner->last_seen ? $chatPartner->last_seen->diffForHumans() : 'long ago' }}
                    @endif
                </div>

                {{-- Area Pesan (Scrollable) --}}
                <div id="chat-box" wire:poll.2000ms class="card-body overflow-auto d-flex flex-column"
                    style="height: 400px; background-color: #f8f9fa;">
                    @php
                        $lastDate = null;
                    @endphp
                    @foreach ($messages as $msg)
                        @php
                            $currentDate = $msg->created_at->format('Y-m-d');
                            $isNewDate = $currentDate != $lastDate;

                            // Tentukan label tanggal
                            $dateLabel = '';
                            if ($msg->created_at->isToday()) {
                                $dateLabel = 'Hari Ini';
                            } elseif ($msg->created_at->isYesterday()) {
                                $dateLabel = 'Kemarin';
                            } else {
                                $dateLabel = $msg->created_at->format('d M Y');
                            }
                        @endphp

                        @if ($isNewDate)
                            <div class="divider text-xs opacity-50 my-4">
                                {{ $dateLabel }}
                            </div>
                            @php $lastDate = $currentDate; @endphp
                        @endif
                        
                        @php
                            $isMe = $msg->user_id == auth()->id();
                            $sender = $msg->user;
                        @endphp

                        <div class="chat {{ $isMe ? 'chat-end' : 'chat-start' }}">
                            <div class="chat-image avatar">
                                <div class="w-10 rounded-full">
                                    <img alt="Avatar"
                                        src="https://ui-avatars.com/api/?name={{ urlencode($sender->name) }}&background=random" />
                                </div>
                            </div>
                            <div class="chat-header">
                                {{ $isMe ? 'Anda' : $sender->name }}
                                <time class="text-xs opacity-50 ml-1">{{ $msg->created_at->format('H:i') }}</time>
                            </div>
                            <div
                                class="chat-bubble {{ $isMe ? 'chat-bubble-primary text-white' : 'chat-bubble-secondary' }}">
                                {{ $msg->body }}
                            </div>
                            <div class="chat-footer opacity-50">
                                @if ($isMe)
                                    <i
                                        class="fas {{ $msg->is_read ? 'fa-check-double text-blue-500' : 'fa-check' }}"></i>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Input Area --}}
                <div class="card-footer bg-white">
                    <form wire:submit.prevent="sendMessage">
                        <div class="input-group">
                            <input type="text" wire:model="body" class="form-control" placeholder="Tulis pesan..."
                                required>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-paper-plane"></i> Kirim
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @else
                {{-- EMPTY STATE (Jika belum klik user) --}}
                <div class="card-body d-flex align-items-center justify-content-center flex-column text-muted h-100">
                    <i class="fas fa-comments fa-4x mb-3 text-gray-300"></i>
                    <h5>Belum ada percakapan dipilih</h5>
                    <p>Silakan pilih chat di sidebar sebelah kiri.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Script Auto Scroll --}}
    {{-- Script Auto Scroll & Clear Input --}}
    <script>
        document.addEventListener('livewire:initialized', () => {
            const chatBox = document.getElementById('chat-box');
            // Ganti selector di bawah jika Anda menggunakan <textarea> bukan <input>
            const chatInput = document.querySelector('input[wire\\:model="body"]');

            const scrollToBottom = () => {
                if (chatBox) {
                    chatBox.scrollTop = chatBox.scrollHeight;
                }
            }

            // 1. Scroll saat pertama kali load
            scrollToBottom();

            // 2. Event Listener saat pesan dikirim/diterima
            Livewire.on('chat-updated', () => {
                // Paksa input jadi kosong secara visual (UX lebih cepat)
                if (chatInput) {
                    chatInput.value = '';
                }

                // Scroll ke bawah dengan sedikit delay agar elemen baru sempat dirender
                setTimeout(() => {
                    scrollToBottom();
                }, 100);
            });
        });
    </script>
</div>
