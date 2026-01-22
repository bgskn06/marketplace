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
                        <img
                            src="{{ auth()->user()->photo
                                ? asset('storage/profile/' . auth()->user()->photo)
                                : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                            class="rounded-circle object-fit-cover"
                            width="40"
                            height="40"
                            alt="Profile">
                    </a>

                    <!-- MOBILE MENU -->
                    <button class="btn btn-light d-md-none">
                        <i class="bi bi-list"></i>
                    </button>
                </div>

            </div>
        </div>
    </header>


    <main class="container my-4">
        <div class="card shadow border-start border-4 border-primary-subtle">
            <div class="card-body">

                <h5 class="fw-semibold text-primary mb-3">Pesanan Terbaru</h5>

                {{-- STATUS TABS --}}
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
                    <div class="btn-group flex-wrap" role="group">

                        <a href="{{ $buyerOrdersUrl }}"
                            class="btn btn-sm {{ is_null($status) ? 'btn-primary' : 'btn-outline-secondary' }}">
                            Semua
                            <span class="badge bg-light text-primary ms-1">
                                {{ array_sum($counts ?? []) }}
                            </span>
                        </a>

                        <a href="{{ $buyerOrdersUrl.'?status='.\App\Models\Order::STATUS_UNPAID }}"
                            class="btn btn-sm {{ ((string)$status === (string)\App\Models\Order::STATUS_UNPAID) ? 'btn-primary' : 'btn-outline-secondary' }}">
                            Belum Bayar
                            <span class="badge bg-light text-primary ms-1">
                                {{ $counts[\App\Models\Order::STATUS_UNPAID] ?? 0 }}
                            </span>
                        </a>

                        <a href="{{ $buyerOrdersUrl.'?status='.\App\Models\Order::STATUS_PAID }}"
                            class="btn btn-sm {{ ((string)$status === (string)\App\Models\Order::STATUS_PAID) ? 'btn-primary' : 'btn-outline-secondary' }}">
                            Perlu Dikirim
                            <span class="badge bg-light text-primary ms-1">
                                {{ $counts[\App\Models\Order::STATUS_PAID] ?? 0 }}
                            </span>
                        </a>

                        <a href="{{ $buyerOrdersUrl.'?status='.\App\Models\Order::STATUS_SHIPPED }}"
                            class="btn btn-sm {{ ((string)$status === (string)\App\Models\Order::STATUS_SHIPPED) ? 'btn-primary' : 'btn-outline-secondary' }}">
                            Dikirim
                            <span class="badge bg-light text-primary ms-1">
                                {{ $counts[\App\Models\Order::STATUS_SHIPPED] ?? 0 }}
                            </span>
                        </a>

                        <a href="{{ $buyerOrdersUrl.'?status='.\App\Models\Order::STATUS_COMPLETED }}"
                            class="btn btn-sm {{ ((string)$status === (string)\App\Models\Order::STATUS_COMPLETED) ? 'btn-primary' : 'btn-outline-secondary' }}">
                            Selesai
                            <span class="badge bg-light text-primary ms-1">
                                {{ $counts[\App\Models\Order::STATUS_COMPLETED] ?? 0 }}
                            </span>
                        </a>

                        <a href="{{ $buyerOrdersUrl.'?status=0' }}"
                            class="btn btn-sm {{ ((string)$status === '0') ? 'btn-primary' : 'btn-outline-secondary' }}">
                            Batal / Refund
                            <span class="badge bg-light text-primary ms-1">
                                {{ ($counts[0] ?? 0) + ($counts[5] ?? 0) }}
                            </span>
                        </a>
                    </div>

                    <div class="text-muted small">
                        Menampilkan <strong>{{ $orders->count() }}</strong> pesanan
                    </div>
                </div>

                {{-- ORDER LIST --}}
                <div class="d-grid gap-2">
                    @forelse ($orders as $order)
                        <x-order-row :order="$order" />
                    @empty
                        <div class="text-muted">Tidak ada pesanan.</div>
                    @endforelse
                </div>

                <div class="text-end mt-4">
                    <a href="#" class="btn btn-primary">Lihat semua pesanan</a>
                </div>

            </div>
        </div>
    </main>
</x-app-layout>
