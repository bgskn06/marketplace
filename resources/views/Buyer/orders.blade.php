<x-app-layout>
    <header class="border-bottom bg-white">
        <div class="container-fluid container-xxl">
            <div class="d-flex align-items-center justify-content-between" style="height:64px">

                <!-- LEFT -->
                <div class="d-flex align-items-center gap-4 flex-grow-1">

                    <!-- LOGO -->
                    <a href="#" class="d-flex align-items-center gap-2 text-decoration-none">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-success">
                            <path d="M9 16v-8l3 5l3 -5v8"></path>
                            <path d="M19.875 6.27a2.225 2.225 0 0 1 1.125 1.948v7.284
              c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27
              a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27
              a2.225 2.225 0 0 1 -1.158 -1.948v-7.285
              c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98
              a2.33 2.33 0 0 1 2.25 0l6.75 3.98"></path>
                        </svg>
                        <span class="fs-5 fw-medium text-dark">
                            MARKETIFY
                        </span>
                    </a>
                </div>

                <!-- RIGHT -->
                <div class="d-flex align-items-center gap-3">

                    <!-- NAV -->
                    <nav class="d-none d-md-block">
                        <ul class="nav align-items-center gap-2">

                            {{-- HOME TAB --}}
                            <li class="nav-item">
                                <a href="{{ route('buyer.dashboard') }}"
                                    class="nav-link px-3 rounded-pill fw-medium transition-all   {{ request()->routeIs('buyer.dashboard')
                                        ? 'bg-success bg-opacity-10 text-success fw-bold shadow-sm'
                                        : 'text-muted hover-text-success' }}">
                                    Home
                                </a>
                            </li>

                            {{-- ORDERS TAB --}}
                            <li class="nav-item">
                                <a href="{{ $buyerOrdersUrl }}"
                                    class="nav-link px-3 rounded-pill fw-medium transition-all 
               {{ request()->routeIs('buyer.orders*')
                   ? 'bg-success bg-opacity-10 text-success fw-bold shadow-sm'
                   : 'text-muted hover-text-success' }}">
                                    Orders
                                </a>
                            </li>

                            {{-- MESSAGES TAB --}}
                            <li class="nav-item">
                                <a href="{{ route('buyer.messages') }}"
                                    class="nav-link px-3 rounded-pill fw-medium transition-all 
               {{ request()->routeIs('buyer.messages*')
                   ? 'bg-success bg-opacity-10 text-success fw-bold shadow-sm'
                   : 'text-muted hover-text-success' }}">
                                    Messages
                                </a>
                            </li>

                            {{-- CART TAB --}}
                            <li class="nav-item">
                                <a href="{{ route('buyer.cart') }}"
                                    class="nav-link px-3 rounded-pill fw-medium transition-all d-flex align-items-center gap-2
               {{ request()->routeIs('buyer.cart*')
                   ? 'bg-success bg-opacity-10 text-success fw-bold shadow-sm'
                   : 'text-muted hover-text-success' }}">
                                    Cart
                                    {{-- Badge Cart --}}
                                    <span
                                        class="badge rounded-pill 
                   {{ request()->routeIs('buyer.cart*') ? 'bg-success text-light' : 'bg-light text-secondary border' }}"
                                        x-text="cartCount" style="font-size: 0.75rem;">
                                    </span>
                                </a>
                            </li>

                        </ul>
                    </nav>

                    <!-- DIVIDER -->
                    <div class="vr d-none d-md-block"></div>

                    <!-- AVATAR -->
                    <a href="{{ route('buyer.profile') }}">
                        <img src="https://images.unsplash.com/photo-1600486913747-55e5470d6f40"
                            class="rounded-circle object-fit-cover" width="40" height="40" alt="Profile">
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
        <div class="bg-white/95 rounded-lg shadow p-4 border-l-4 border-indigo-300">
            <h2 class="font-semibold text-indigo-700 text-lg">Pesanan Terbaru</h2>

            {{-- Status Tabs --}}
            <div class="mt-3 mb-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <a href="{{ $buyerOrdersUrl }}"
                        class="px-3 py-1 rounded-md text-sm {{ is_null($status) ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700' }}">Semua
                        <span
                            class="ml-2 inline-block bg-white text-xs px-2 rounded-full text-indigo-700">{{ array_sum($counts ?? []) }}</span></a>

                    <a href="{{ $buyerOrdersUrl . '?status=' . \App\Models\Order::STATUS_UNPAID }}"
                        class="px-3 py-1 rounded-md text-sm {{ (string) $status === (string) \App\Models\Order::STATUS_UNPAID ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700' }}">Belum
                        Bayar <span
                            class="ml-2 inline-block bg-white text-xs px-2 rounded-full text-indigo-700">{{ $counts[\App\Models\Order::STATUS_UNPAID] ?? 0 }}</span></a>

                    <a href="{{ $buyerOrdersUrl . '?status=' . \App\Models\Order::STATUS_PAID }}"
                        class="px-3 py-1 rounded-md text-sm {{ (string) $status === (string) \App\Models\Order::STATUS_PAID ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700' }}">Perlu
                        Dikirim <span
                            class="ml-2 inline-block bg-white text-xs px-2 rounded-full text-indigo-700">{{ $counts[\App\Models\Order::STATUS_PAID] ?? 0 }}</span></a>

                    <a href="{{ $buyerOrdersUrl . '?status=' . \App\Models\Order::STATUS_SHIPPED }}"
                        class="px-3 py-1 rounded-md text-sm {{ (string) $status === (string) \App\Models\Order::STATUS_SHIPPED ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700' }}">Dikirim
                        <span
                            class="ml-2 inline-block bg-white text-xs px-2 rounded-full text-indigo-700">{{ $counts[\App\Models\Order::STATUS_SHIPPED] ?? 0 }}</span></a>

                    <a href="{{ $buyerOrdersUrl . '?status=' . \App\Models\Order::STATUS_COMPLETED }}"
                        class="px-3 py-1 rounded-md text-sm {{ (string) $status === (string) \App\Models\Order::STATUS_COMPLETED ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700' }}">Selesai
                        <span
                            class="ml-2 inline-block bg-white text-xs px-2 rounded-full text-indigo-700">{{ $counts[\App\Models\Order::STATUS_COMPLETED] ?? 0 }}</span></a>

                    <a href="{{ $buyerOrdersUrl . '?status=0' }}"
                        class="px-3 py-1 rounded-md text-sm {{ (string) $status === '0' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700' }}">Batal
                        / Refund <span
                            class="ml-2 inline-block bg-white text-xs px-2 rounded-full text-indigo-700">{{ ($counts[0] ?? 0) + ($counts[5] ?? 0) }}</span></a>
                </div>

                <div class="text-sm text-gray-500">Menampilkan <strong>{{ $orders->count() }}</strong> pesanan</div>
            </div>

            <div class="mt-4 space-y-3">
                @forelse ($orders as $order)
                    <x-order-row :order="$order" />
                @empty
                    <div class="text-sm text-gray-600">Tidak ada pesanan.</div>
                @endforelse
            </div>

            <div class="mt-4 text-right">
                <a href="#" class="inline-block bg-indigo-600 text-white px-4 py-2 rounded-md">Lihat semua
                    pesanan</a>
            </div>
        </div>
    </main>
</x-app-layout>
