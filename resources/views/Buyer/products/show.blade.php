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




    <main class="container py-4">
        <div class="row g-4 align-items-start">
            {{-- Header --}}
            <div class="col-12 col-lg-3">
                <h1 class="h4 fw-semibold text-dark">{{ $product->name }}</h1>
                <div class="text-warning small">{{ number_format($product->rating ?? 0, 1) }} ★</div>
            </div>

            <div class="col-12 col-lg-9">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row g-4">
                            {{-- Image Section --}}
                            <div class="col-12 col-md-6">
                                <div class="bg-light rounded overflow-hidden">
                                    @if ($mainImg)
                                        <img id="main-product-image"
                                            src="{{ $mainImg }}"
                                            class="img-fluid w-100 rounded"
                                            style="height:380px; object-fit:cover;"
                                            alt="{{ $product->name }}">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center text-muted"
                                            style="height:380px;">
                                            No image
                                        </div>
                                    @endif
                                </div>

                                @if ($product->photos->count() > 1)
                                    <div class="row g-2 mt-3">
                                        @foreach ($product->photos as $photo)
                                            <div class="col-3">
                                                <button type="button"
                                                        class="border-0 p-0 bg-transparent w-100"
                                                        onclick="document.getElementById('main-product-image').src='{{ asset('storage/'.$photo->path) }}'">
                                                    <img src="{{ asset('storage/'.$photo->path) }}"
                                                        class="img-fluid rounded"
                                                        style="height:70px; object-fit:cover;">
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            {{-- Product Info --}}
                            <div class="col-12 col-md-6">
                                <h2 class="h5 fw-bold">{{ $product->name }}</h2>
                                <div class="text-muted small">{{ $product->category }}</div>

                                <div class="mt-3 h3 fw-bold text-primary">
                                    Rp{{ number_format($product->price ?? ($product->harga ?? 0), 0, ',', '.') }}
                                </div>

                                <div class="mt-3 text-secondary">
                                    {!! nl2br(e($product->description)) !!}
                                </div>

                                {{-- Action Buttons --}}
                                <div class="mt-4 d-flex flex-wrap gap-2">
                                    {{-- CHAT --}}
                                    @auth
                                        @if (auth()->id() !== $product->user_id)
                                            <form action="{{ route('chat.start', $product->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-primary d-flex align-items-center gap-2">
                                                    Chat Penjual
                                                </button>
                                            </form>
                                        @else
                                            <button disabled class="btn btn-outline-secondary">
                                                Produk Anda
                                            </button>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-outline-primary">
                                            Chat Login
                                        </a>
                                    @endauth

                                    {{-- CART --}}
                                    <form method="POST" action="{{ route('buyer.cart.add') }}">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                                            + Keranjang
                                        </button>
                                    </form>
                                </div>

                                {{-- Reviews --}}
                                <div id="reviews" class="card mt-4">
                                    <div class="card-body">
                                        <h6 class="fw-semibold mb-3">Ulasan Pembeli</h6>

                                        <div class="text-warning small mb-2">
                                            {{ number_format($product->rating ?? 0, 1) }} ★
                                        </div>

                                        @if (session('error'))
                                            <div class="alert alert-danger py-1 small">{{ session('error') }}</div>
                                        @endif
                                        @if (session('success'))
                                            <div class="alert alert-success py-1 small">{{ session('success') }}</div>
                                        @endif

                                        @auth
                                            @if ($canRate)
                                                <form method="POST" action="{{ route('buyer.products.reviews.store', $product) }}">
                                                    @csrf
                                                    <div class="mb-2">
                                                        <label class="form-label small">Nilai</label>
                                                        <select name="rating" class="form-select form-select-sm">
                                                            @for ($i = 5; $i >= 1; $i--)
                                                                <option value="{{ $i }}"
                                                                    {{ (old('rating') ?? ($existingReview->rating ?? '')) == $i ? 'selected' : '' }}>
                                                                    {{ $i }} ★
                                                                </option>
                                                            @endfor
                                                        </select>
                                                    </div>

                                                    <div class="mb-2">
                                                        <label class="form-label small">Ulasan</label>
                                                        <textarea name="comment"
                                                                class="form-control form-control-sm"
                                                                rows="3">{{ old('comment') ?? ($existingReview->comment ?? '') }}</textarea>
                                                    </div>

                                                    <button class="btn btn-primary btn-sm">Kirim Ulasan</button>
                                                </form>
                                            @else
                                                <div class="text-muted small">
                                                    Anda dapat memberi rating setelah pesanan diterima.
                                                </div>
                                            @endif
                                        @else
                                            <div class="text-muted small">
                                                Silakan <a href="{{ route('login') }}">login</a> untuk memberi rating.
                                            </div>
                                        @endauth

                                        <hr>

                                        @forelse($product->reviews()->with('user')->latest()->get() as $review)
                                            <div class="border rounded p-2 mb-2">
                                                <div class="fw-semibold small">
                                                    {{ $review->user->name ?? 'User' }}
                                                    <span class="text-warning">★ {{ $review->rating }}</span>
                                                </div>
                                                <div class="text-muted small">{{ $review->comment }}</div>
                                                <div class="text-muted small">{{ optional($review->created_at)->format('Y-m-d') }}</div>
                                            </div>
                                        @empty
                                            <div class="text-muted small">Belum ada ulasan.</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Detail --}}
                <div class="card shadow-sm mt-4">
                    <div class="card-body">
                        <h6 class="fw-semibold">Detail Produk</h6>
                        <div class="text-secondary small">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

</x-app-layout>
