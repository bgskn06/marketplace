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

                                {{-- === BADGE UNREAD (Ganti badge-error ke badge-danger) === --}}
                                @if ($conv->unread_messages_count > 0)
                                    <span class="ml-2 badge badge-danger text-white">
                                        {{ $conv->unread_messages_count }}
                                    </span>
                                @endif
                            </div>

                            <small class="{{ $isActive ? 'text-white' : 'text-muted' }}">
                                {{ $conv->last_message_at ? $conv->last_message_at->format('H:i') : '' }}
                            </small>
                        </div>

                        <small class="{{ $isActive ? 'text-white' : 'text-secondary' }} d-block text-truncate">
                            {{-- Preview message, cek apakah produk atau teks --}}
                            @if ($conv->latestMessage && $conv->latestMessage->product_id)
                                <i class="fas fa-shopping-bag"></i> Produk...
                            @else
                                {{ $conv->latestMessage->body ?? 'Belum ada pesan' }}
                            @endif
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
        <div class="card shadow-sm" style="height: 580px;">

            @if ($selectedConversation)
                @php
                    $chatPartner =
                        $selectedConversation->sender_id == auth()->id()
                            ? $selectedConversation->receiver
                            : $selectedConversation->sender;
                @endphp

                {{-- Header Chat Room --}}
                <div class="card-header bg-white d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        {{-- Avatar Header --}}
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($chatPartner->name) }}&background=random"
                            class="rounded-circle mr-2" style="width: 35px; height: 35px;">
                        <div>
                            <h6 class="m-0 font-weight-bold text-primary">{{ $chatPartner->name }}</h6>
                            <small class="text-muted">
                                @if ($chatPartner->isOnline())
                                    <span class="text-success"><i class="fas fa-circle" style="font-size: 8px;"></i>
                                        Online</span>
                                @else
                                    Last seen
                                    {{ $chatPartner->last_seen ? $chatPartner->last_seen->diffForHumans() : 'offline' }}
                                @endif
                            </small>
                        </div>
                    </div>
                </div>

                {{-- Area Pesan (Scrollable) --}}
                <div id="chat-box" wire:poll.2000ms class="card-body overflow-auto bg-light"
                    style="height: 400px; display: flex; flex-direction: column;">

                    @php $lastDate = null; @endphp

                    @foreach ($messages as $msg)
                        @php
                            $currentDate = $msg->created_at->format('Y-m-d');
                            $isNewDate = $currentDate != $lastDate;

                            $dateLabel = '';
                            if ($msg->created_at->isToday()) {
                                $dateLabel = 'Hari Ini';
                            } elseif ($msg->created_at->isYesterday()) {
                                $dateLabel = 'Kemarin';
                            } else {
                                $dateLabel = $msg->created_at->format('d M Y');
                            }

                            $isMe = $msg->user_id == auth()->id();
                            $sender = $msg->user;
                        @endphp

                        {{-- DIVIDER TANGGAL --}}
                        @if ($isNewDate)
                            <div class="w-100 text-center my-3">
                                <span class="badge badge-light text-secondary border px-3 py-1 font-weight-normal">
                                    {{ $dateLabel }}
                                </span>
                            </div>
                            @php $lastDate = $currentDate; @endphp
                        @endif

                        {{-- WRAPPER PESAN (FLEX ROW) --}}
                        <div class="d-flex mb-3 w-100 {{ $isMe ? 'justify-content-end' : 'justify-content-start' }}">

                            {{-- Avatar Lawan (Hanya muncul jika pesan dari lawan) --}}
                            @if (!$isMe)
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($sender->name) }}&background=random"
                                    class="rounded-circle mr-2 align-self-end mb-1" style="width: 30px; height: 30px;"
                                    title="{{ $sender->name }}">
                            @endif

                            {{-- BUBBLE CHAT --}}
                            <div class="position-relative" style="max-width: 70%;">

                                {{-- A. JIKA INI PESAN NEGO --}}
                                @if ($msg->type == 'negotiation' && $msg->product)
                                    {{-- Logic Warna Border berdasarkan Status (0,1,2) --}}
                                    @php
                                        $borderColor = '#f6c23e'; // 0: Pending (Kuning)
                                        if ($msg->offer_status == 1) {
                                            $borderColor = '#1cc88a';
                                        } // 1: Accepted (Hijau)
                                        if ($msg->offer_status == 2) {
                                            $borderColor = '#e74a3b';
                                        } // 2: Rejected (Merah)
                                    @endphp

                                    <div class="card shadow-sm border-0 overflow-hidden mb-1"
                                        style="border-radius: 15px; border-left: 6px solid {{ $borderColor }};">

                                        <div class="card-body p-3 bg-white text-dark">
                                            {{-- Header Status --}}
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <strong class="text-uppercase small text-muted"
                                                    style="font-size: 0.7rem;">
                                                    <i class="fas fa-tag"></i> Tawar Harga
                                                </strong>

                                                @if ($msg->offer_status == 0)
                                                    <span class="badge badge-warning text-dark">Menunggu Respon</span>
                                                @elseif($msg->offer_status == 1)
                                                    <span class="badge badge-success">Diterima</span>
                                                @elseif($msg->offer_status == 2)
                                                    <span class="badge badge-danger">Ditolak</span>
                                                @endif
                                            </div>

                                            {{-- Preview Produk (Kecil) --}}
                                            <div class="d-flex align-items-center mb-3 p-2 rounded bg-light border">
                                                <div class="mr-2 border rounded overflow-hidden"
                                                    style="width: 40px; height: 40px; flex-shrink: 0;">
                                                    @php
                                                        $photoUrl = $msg->product->mainPhoto
                                                            ? asset('storage/' . $msg->product->mainPhoto->path)
                                                            : null;
                                                    @endphp
                                                    <img src="{{ $photoUrl ?? 'https://via.placeholder.com/40' }}"
                                                        class="w-100 h-100" style="object-fit: cover;">
                                                </div>
                                                <div style="line-height: 1.2; min-width: 0;">
                                                    <div class="small font-weight-bold text-truncate text-dark">
                                                        {{ $msg->product->name }}</div>
                                                    <div class="small text-muted text-decoration-line-through">Rp
                                                        {{ number_format($msg->product->price) }}</div>
                                                </div>
                                            </div>

                                            {{-- Harga Tawaran --}}
                                            <div class="text-center mb-3">
                                                <div class="small text-muted">Ditawar menjadi:</div>
                                                <h5 class="font-weight-bold text-primary mb-0">Rp
                                                    {{ number_format($msg->offer_price) }}</h5>
                                            </div>

                                            {{-- TOMBOL AKSI (Hanya muncul jika Pending (0) & Saya bukan pengirim tawaran) --}}
                                            @if ($msg->offer_status == 0 && !$isMe)
                                                <div class="d-flex justify-content-between">
                                                    <button wire:click="rejectOffer({{ $msg->id }})"
                                                        class="btn btn-outline-danger btn-sm flex-fill mr-1">
                                                        <i class="fas fa-times"></i> Tolak
                                                    </button>
                                                    <button wire:click="acceptOffer({{ $msg->id }})"
                                                        class="btn btn-success btn-sm flex-fill ml-1">
                                                        <i class="fas fa-check"></i> Terima
                                                    </button>
                                                </div>
                                            @endif

                                            {{-- Pesan Teks Tambahan --}}
                                            @if ($msg->body)
                                                <hr class="my-2">
                                                <p class="mb-0 small text-secondary">"{{ $msg->body }}"</p>
                                            @endif
                                        </div>

                                        {{-- Footer Jam untuk Nego --}}
                                        <div
                                            class="px-3 pb-2 bg-white d-flex align-items-center justify-content-end text-muted small">
                                            <span class="mr-1"
                                                style="font-size: 0.65rem;">{{ $msg->created_at->format('H:i') }}</span>
                                            @if ($isMe)
                                                <i class="fas {{ $msg->is_read ? 'fa-check-double' : 'fa-check' }}"
                                                    style="font-size: 0.65rem;"></i>
                                            @endif
                                        </div>
                                    </div>


                                    {{-- B. JIKA PESAN BIASA (Logic Lama Anda) --}}
                                @else
                                    {{-- Nama Pengirim (Kecil di atas bubble) --}}
                                    @if (!$isMe)
                                        <small class="text-muted ml-1"
                                            style="font-size: 0.7rem;">{{ $sender->name }}</small>
                                    @endif

                                    <div class="p-3 shadow-sm {{ $isMe ? 'bg-primary text-white' : 'bg-white text-dark' }}"
                                        style="border-radius: 15px; border-bottom-{{ $isMe ? 'right' : 'left' }}-radius: 2px;">

                                        {{-- === LOGIC CARD PRODUK === --}}
                                        @if ($msg->product)
                                            <div class="card border-0 mb-2 overflow-hidden text-left"
                                                style="cursor: pointer; background-color: {{ $isMe ? 'rgba(255,255,255,0.9)' : '#f1f3f5' }};"
                                                onclick="window.open('{{ route('products.show', $msg->product->id) }}', '_blank')">

                                                <div class="d-flex p-2 align-items-center">
                                                    {{-- Gambar Produk --}}
                                                    {{-- LOGIC GAMBAR POLYMORPH --}}
                                                    <div class="mr-2 border rounded bg-light d-flex align-items-center justify-content-center"
                                                        style="width: 50px; height: 50px; flex-shrink: 0; overflow: hidden;">

                                                        @php
                                                            // Ambil relasi mainPhoto
                                                            // GANTI 'path' dengan nama kolom di tabel photos Anda (misal: 'filename' atau 'url')
                                                            $photoUrl = $msg->product->mainPhoto
                                                                ? asset('storage/' . $msg->product->mainPhoto->path)
                                                                : null;
                                                        @endphp

                                                        @if ($photoUrl)
                                                            <img src="{{ $photoUrl }}"
                                                                alt="{{ $msg->product->name }}" class="w-100 h-100"
                                                                style="object-fit: cover;"
                                                                onerror="this.onerror=null; this.src='https://via.placeholder.com/50x50?text=No+Img';">
                                                        @else
                                                            {{-- Fallback jika produk tidak punya foto sama sekali --}}
                                                            <i class="fas fa-image text-secondary"></i>
                                                        @endif
                                                    </div>

                                                    {{-- Info Produk --}}
                                                    <div style="min-width: 0;">
                                                        <p class="mb-0 font-weight-bold text-dark text-truncate"
                                                            style="font-size: 0.85rem; max-width: 150px;">
                                                            {{ $msg->product->name }}
                                                        </p>
                                                        <p class="mb-0 text-primary font-weight-bold"
                                                            style="font-size: 0.8rem;">
                                                            Rp {{ number_format($msg->product->price) }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- TEXT PESAN --}}
                                        @if ($msg->body)
                                            <p class="mb-1" style="word-wrap: break-word;">{{ $msg->body }}</p>
                                        @endif

                                        {{-- FOOTER PESAN (Jam & Icon) --}}
                                        <div class="d-flex align-items-center justify-content-end"
                                            style="font-size: 0.7rem; opacity: {{ $isMe ? '0.8' : '0.6' }};">

                                            <span class="mr-1">{{ $msg->created_at->format('H:i') }}</span>

                                            @if ($isMe)
                                                <i
                                                    class="fas {{ $msg->is_read ? 'fa-check-double' : 'fa-check' }}"></i>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- TOMBOL HAPUS (Di luar bubble) --}}
                                @if ($isMe)
                                    <div class="text-right mt-1">
                                        <a href="javascript:void(0)" class="text-muted small"
                                            style="font-size: 0.7rem;" onclick="confirmDelete({{ $msg->id }})">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Input Area --}}
                <div class="card-footer bg-white">
                    <form wire:submit.prevent="sendMessage">
                        <div class="input-group">

                            {{-- TOMBOL NEGO / TAWAR --}}
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-outline-warning" data-toggle="modal"
                                    data-target="#modalNego" title="Buat Penawaran / Nego">
                                    <i class="fas fa-tag"></i>
                                </button>
                            </div>

                            <input type="text" wire:model="body" class="form-control"
                                placeholder="Tulis pesan..." required>

                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-paper-plane"></i> Kirim
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @else
                {{-- EMPTY STATE --}}
                <div class="card-body d-flex align-items-center justify-content-center flex-column text-muted h-100">
                    <i class="fas fa-comments fa-4x mb-3 text-gray-300"></i>
                    <h5>Belum ada percakapan dipilih</h5>
                    <p>Silakan pilih chat di sidebar sebelah kiri.</p>
                </div>
            @endif
        </div>
    </div>
    {{-- === MODAL POPUP NEGO (Taruh di paling bawah file, sebelum <script>) === --}}
    <div wire:ignore.self class="modal fade" id="modalNego" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title font-weight-bold">Tawar Harga</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- Pilihan Produk (Hardcode dulu atau ambil dari properti) --}}
                    <div class="form-group position-relative">
                        <label class="small text-muted">Produk yang ditawar</label>

                        {{-- A. JIKA PRODUK SUDAH DIPILIH (Tampilkan Card Kecil) --}}
                        @if ($negotiationProductId && $selectedProductPreview)
                            <div class="border rounded p-2 d-flex justify-content-between align-items-center bg-light">
                                <div class="d-flex align-items-center">
                                    {{-- Gambar Kecil --}}
                                    <div class="mr-2 border rounded overflow-hidden"
                                        style="width: 35px; height: 35px;">
                                        @php
                                            $img = $selectedProductPreview->mainPhoto
                                                ? asset('storage/' . $selectedProductPreview->mainPhoto->path)
                                                : null;
                                        @endphp
                                        <img src="{{ $img ?? 'https://via.placeholder.com/35' }}" class="w-100 h-100"
                                            style="object-fit: cover;">
                                    </div>
                                    <div style="line-height: 1.1;">
                                        <div class="font-weight-bold small text-truncate" style="max-width: 160px;">
                                            {{ $selectedProductPreview->name }}
                                        </div>
                                        <div class="text-primary small">
                                            Rp {{ number_format($selectedProductPreview->price) }}
                                        </div>
                                    </div>
                                </div>

                                {{-- Tombol Hapus (X) --}}
                                <button type="button" wire:click="removeSelectedProduct"
                                    class="btn btn-sm text-danger">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            {{-- B. JIKA BELUM MEMILIH (Tampilkan Input Search) --}}
                        @else
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0"><i
                                            class="fas fa-search text-muted small"></i></span>
                                </div>
                                {{-- wire:model.live.debounce.300ms = Tunggu 300ms setelah mengetik baru request ke server (biar hemat resource) --}}
                                <input type="text" wire:model.live.debounce.300ms="searchKeyword"
                                    class="form-control border-left-0" placeholder="Ketik nama produk...">
                            </div>

                            {{-- C. HASIL PENCARIAN (DROPDOWN) --}}
                            @if (!empty($searchResults) && count($searchResults) > 0)
                                <div class="position-absolute w-100 bg-white shadow-sm border rounded mt-1"
                                    style="z-index: 1050; max-height: 200px; overflow-y: auto;">

                                    <ul class="list-group list-group-flush">
                                        @foreach ($searchResults as $res)
                                            <li class="list-group-item list-group-item-action p-2"
                                                style="cursor: pointer;"
                                                wire:click="selectProductForNego({{ $res->id }})">

                                                <div class="d-flex align-items-center">
                                                    <div class="mr-2" style="width: 30px; height: 30px;">
                                                        {{-- Logic Gambar --}}
                                                        @php
                                                            $resImg = $res->mainPhoto
                                                                ? asset('storage/' . $res->mainPhoto->path)
                                                                : null;
                                                        @endphp
                                                        <img src="{{ $resImg ?? 'https://via.placeholder.com/30' }}"
                                                            class="w-100 h-100 rounded" style="object-fit: cover;">
                                                    </div>
                                                    <div>
                                                        <div class="small font-weight-bold">{{ $res->name }}</div>
                                                        <div class="small text-muted">Rp
                                                            {{ number_format($res->price) }}</div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @elseif(strlen($searchKeyword) >= 2)
                                {{-- Jika tidak ketemu --}}
                                <div class="position-absolute w-100 bg-white shadow-sm border rounded mt-1 p-2 text-center text-muted small"
                                    style="z-index: 1050;">
                                    Produk tidak ditemukan.
                                </div>
                            @endif

                            @error('negotiationProductId')
                                <span class="text-danger small d-block mt-1">Wajib pilih produk!</span>
                            @enderror
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="small text-muted">Harga Tawaran (Rp)</label>
                        <input type="number" wire:model="offerAmount"
                            class="form-control font-weight-bold text-primary" placeholder="Contoh: 50000">
                    </div>
                </div>
                <div class="modal-footer p-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button type="button" wire:click="sendOfferMessage"
                        class="btn btn-warning btn-sm font-weight-bold" data-dismiss="modal">
                        Kirim Tawaran
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('livewire:initialized', () => {
        const scrollToBottom = () => {
            const chatBox = document.getElementById('chat-box');
            if (chatBox) {
                chatBox.scrollTo({
                    top: chatBox.scrollHeight,
                    behavior: 'smooth'
                });
            }
        }

        scrollToBottom();

        Livewire.on('chat-updated', () => {
            const chatInput = document.querySelector('input[wire\\:model="body"]');
            if (chatInput) chatInput.value = '';
            setTimeout(() => scrollToBottom(), 100);
        });

        Livewire.on('message-deleted', () => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Pesan berhasil dihapus',
                showConfirmButton: false,
                timer: 3000
            });
        });
    });

    // Helper function untuk SweetAlert Hapus (Di luar Livewire event agar bersih)
    function confirmDelete(msgId) {
        Swal.fire({
            title: 'Hapus pesan ini?',
            text: 'Pesan akan dihapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Panggil method Livewire dari JS
                @this.deleteMessage(msgId);
            }
        });
    }
</script>
