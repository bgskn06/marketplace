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



    <main class="container-fluid container-xxl my-4">
    <div class="row g-4">

        {{-- MAIN CONTENT --}}
        <div class="col-lg-8">

            {{-- SHOP HEADER --}}
            <div class="card shadow mb-4">
                <div class="card-body d-flex gap-4 align-items-center">

                    <div class="bg-light rounded overflow-hidden d-flex align-items-center justify-content-center"
                         style="width:96px;height:96px">
                        @if($shop->logo)
                            <img src="{{ asset('storage/'.$shop->logo) }}"
                                 class="w-100 h-100 object-fit-cover"
                                 alt="{{ $shop->name }}">
                        @else
                            <span class="text-muted small">No Logo</span>
                        @endif
                    </div>

                    <div>
                        <h4 class="fw-bold mb-1">{{ $shop->name }}</h4>

                        @if($shop->user)
                            <div class="text-muted small">
                                Penjual: {{ $shop->user->name }}
                            </div>
                        @endif

                        <div class="text-muted small mt-1">
                            {{ $shop->address ?? 'Alamat tidak tersedia' }}
                        </div>

                        <div class="mt-3 d-flex gap-2 flex-wrap">
                            @auth
                                @if($isFollowing)
                                    <form method="POST" action="{{ route('shops.unfollow', $shop) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-secondary btn-sm">
                                            Berhenti Mengikuti
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('shops.follow', $shop) }}">
                                        @csrf
                                        <button class="btn btn-primary btn-sm">
                                            Follow
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('seller.chat', $shop->user) }}"
                                   class="btn btn-outline-primary btn-sm">
                                    Hubungi Penjual
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                   class="btn btn-primary btn-sm">
                                    Login untuk Follow
                                </a>
                                <a href="{{ route('login') }}"
                                   class="btn btn-outline-primary btn-sm">
                                    Login untuk Hubungi
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            {{-- PRODUCTS --}}
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">
                        Produk dari {{ $shop->name }}
                    </h5>

                    <div class="row g-4">
                        @forelse($products as $product)
                            <div class="col-6 col-md-4 col-lg-3">
                                <x-product-card :product="$product" />
                            </div>
                        @empty
                            <div class="col-12 text-muted small">
                                Tidak ada produk.
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4 d-flex justify-content-center">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>

            {{-- REVIEWS --}}
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="fw-semibold mb-2">Ulasan Toko</h5>

                    <div class="text-warning small mb-3">
                        Rating saat ini: {{ number_format($shop->rating ?? 0,1) }} ★
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger py-2 small">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success py-2 small">
                            {{ session('success') }}
                        </div>
                    @endif

                    @auth
                        @if($canReview)
                            @php
                                $existing = $shop->reviews()
                                    ->where('user_id', auth()->id())
                                    ->first();
                            @endphp

                            <form method="POST"
                                  action="{{ route('shops.reviews.store', $shop) }}"
                                  class="mb-4">
                                @csrf

                                <div class="mb-2">
                                    <label class="form-label small">Nilai</label>
                                    <select name="rating" class="form-select form-select-sm">
                                        @for($i=5;$i>=1;$i--)
                                            <option value="{{ $i }}"
                                                {{ (old('rating') ?? $existing->rating ?? '') == $i ? 'selected' : '' }}>
                                                {{ $i }} ★
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label small">Ulasan (opsional)</label>
                                    <textarea name="comment"
                                              class="form-control form-control-sm"
                                              rows="3">{{ old('comment') ?? $existing->comment ?? '' }}</textarea>
                                </div>

                                <button class="btn btn-primary btn-sm">
                                    Kirim Ulasan
                                </button>
                            </form>
                        @else
                            <div class="text-muted small mb-3">
                                Anda dapat memberi ulasan untuk toko ini sekarang tanpa harus menunggu pesanan selesai.
                            </div>
                        @endif
                    @else
                        <div class="text-muted small mb-3">
                            Silakan <a href="{{ route('login') }}">login</a> untuk memberi ulasan.
                        </div>
                    @endauth

                    <div class="d-flex flex-column gap-3">
                        @forelse($shopReviews as $review)
                            <div class="border rounded p-3">
                                <div class="fw-medium small">
                                    {{ $review->user->name ?? 'User' }}
                                    • <span class="text-warning">{{ $review->rating }} ★</span>
                                </div>
                                <div class="text-muted small mt-1">
                                    {{ $review->comment }}
                                </div>
                                <div class="text-muted small mt-1">
                                    {{ optional($review->created_at)->format('Y-m-d') }}
                                </div>
                            </div>
                        @empty
                            <div class="text-muted small">
                                Belum ada ulasan untuk toko ini.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- SIDEBAR --}}
        <aside class="col-lg-4 d-none d-lg-block">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h6 class="fw-semibold">Tentang Toko</h6>
                    <div class="text-muted small mt-2">
                        Informasi singkat mengenai toko, kebijakan, dsb.
                    </div>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <h6 class="fw-semibold">Rating & Statistik</h6>
                    <div class="text-muted small mt-2">
                        Rating rata-rata:
                        <strong class="text-primary">
                            {{ number_format($shop->rating ?? 0,1) }}
                        </strong>
                    </div>
                    <div class="text-muted small">
                        Jumlah produk:
                        <strong>{{ $shop->products()->count() }}</strong>
                    </div>
                </div>
            </div>
        </aside>

    </div>
</main>

</x-app-layout>
