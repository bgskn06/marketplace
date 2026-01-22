<x-app-layout>
    <header class="border-bottom bg-white">
        <div class="container-fluid container-xxl">
            <div class="d-flex align-items-center justify-content-between" style="height:64px">

                <!-- LEFT -->
                <div class="d-flex align-items-center gap-4 flex-grow-1">

                    <!-- LOGO -->
                    <a href="#" class="d-flex align-items-center gap-2 text-decoration-none">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-success">
                            <path d="M9 16v-8l3 5l3 -5v8"></path>
                            <path d="M19.875 6.27a2.225 2.225 0 0 1 1.125 1.948v7.284
                c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27
                a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27
                a2.225 2.225 0 0 1 -1.158 -1.948v-7.285
                c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98
                a2.33 2.33 0 0 1 2.25 0l6.75 3.98"></path>
                        </svg>
                        <span class="fs-5 fw-medium text-dark">
                            Marketplace <span class="fw-bold">UDB</span>
                        </span>
                    </a>

                    <!-- SEARCH -->
                    <form method="GET" action="{{ route('buyer.search') }}" class="d-none d-sm-block position-relative">

                        <div class="flex-1 relative">
                            <input name="q" value="{{ request('q') }}" type="search" placeholder="Cari produk, seller, kategori..." class="form-control rounded-pill ps-4 pe-5" style="width:260px" />
                        </div>
                        <button type="submit" class="btn position-absolute top-50 end-0 translate-middle-y me-2 p-1">
                            <i class="bi bi-search text-muted"></i>
                        </button>
                    </form>
                </div>

                <!-- RIGHT -->
                <div class="d-flex align-items-center gap-3">

                    <!-- NAV -->
                    <nav class="d-none d-md-block">
                        <ul class="nav align-items-center gap-2">
                            <li class="nav-item">
                                <x-nav-link href="{{ route('buyer.dashboard') }}" :active="request()->routeIs('buyer.dashboard')">
                                    Home
                                </x-nav-link>
                            </li>

                            <li class="nav-item">
                                <x-nav-link href="{{ route('buyer.orders') }}" :active="request()->routeIs('buyer.orders')">
                                    Orders
                                </x-nav-link>
                            </li>

                            <li class="nav-item">
                                <x-nav-link href="{{ route('buyer.messages') }}" :active="request()->routeIs('buyer.messages')">
                                    Messages
                                </x-nav-link>
                            </li>

                            <li class="nav-item">
                                <x-nav-link href="{{ route('buyer.cart') }}" :active="request()->routeIs('buyer.cart')">
                                    Cart
                                </x-nav-link>
                            </li>
                        </ul>

                    </nav>

                    <!-- DIVIDER -->
                    <div class="vr d-none d-md-block"></div>

                    <!-- AVATAR -->
                    <a href="{{ route('buyer.profile') }}">
                        <img src="{{ auth()->user()->photo
                                ? asset('storage/profile/' . auth()->user()->photo)
                                : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}" class="rounded-circle object-fit-cover" width="40" height="40" alt="Profile">
                    </a>

                    <!-- MOBILE MENU -->
                    <button class="btn btn-light d-md-none">
                        <i class="bi bi-list"></i>
                    </button>
                </div>

            </div>
        </div>
    </header>



    <main class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="bg-white rounded shadow p-4">
            <h2 class="font-semibold text-gray-800">Chat dengan {{ ($conversation->sender_id == auth()->id()) ? ($conversation->receiver->name ?? 'User') : ($conversation->sender->name ?? 'User') }}</h2>

            <div class="mt-4 space-y-3 max-h-96 overflow-auto">
                @foreach($messages as $message)
                <div class="p-2 rounded {{ $message->user_id == auth()->id() ? 'bg-indigo-50 text-right' : 'bg-gray-100 text-left' }}">
                    <div class="text-sm text-gray-700">{{ $message->body }}</div>
                    <div class="text-xs text-gray-400 mt-1">{{ optional($message->created_at)->format('Y-m-d H:i') }}</div>
                </div>
                @endforeach
            </div>

            <form method="POST" action="{{ route('buyer.chat.message', $conversation) }}" class="mt-4">
                @csrf
                <div class="flex gap-2">
                    <input name="message" class="flex-1 border-gray-200 rounded-md p-2" placeholder="Tulis pesan..." />
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded-md">Kirim</button>
                </div>
            </form>
        </div>
    </main>
</x-app-layout>
