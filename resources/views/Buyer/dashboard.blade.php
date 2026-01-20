<x-app-layout>
    <style>
        .carousel-banner {
            height: 400px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* Tablet */
        @media (max-width: 992px) {
            .carousel-banner {
                height: 400px;
            }
        }

        /* Mobile */
        @media (max-width: 576px) {
            .carousel-banner {
                height: 280px;
            }
        }

    </style>

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
                                <a href="{{ route('buyer.dashboard') }}" class="nav-link active bg-success bg-opacity-10 text-success rounded px-3">Home</a>

                            </li>
                            <li class="nav-item">
                                <a href="{{ route('buyer.orders') }}" class="nav-link text-muted">Orders</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('buyer.messages') }}" class="nav-link text-muted">Messages</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('buyer.cart') }}" class="nav-link text-muted">Cart <span class="ml-1 inline-block bg-indigo-50 text-xs px-2 rounded-full text-indigo-700" x-text="cartCount"></span></a>
                            </li>
                        </ul>
                    </nav>

                    <!-- DIVIDER -->
                    <div class="vr d-none d-md-block"></div>

                    <!-- AVATAR -->
                    <a href="{{ route('buyer.profile') }}">
                        <img src="https://images.unsplash.com/photo-1600486913747-55e5470d6f40" class="rounded-circle object-fit-cover" width="40" height="40" alt="Profile">
                    </a>

                    <!-- MOBILE MENU -->
                    <button class="btn btn-light d-md-none">
                        <i class="bi bi-list"></i>
                    </button>
                </div>

            </div>
        </div>
    </header>

    <div class="min-h-screen bg-gradient-to-b from-indigo-50 via-white to-pink-50" x-data="{ filtersOpen:false, cartCount: {{ $summary['cart_count'] ?? 0 }} }">
        <main class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">

            {{-- Banner --}}
            <section class="mb-6">
                <div class="rounded-lg bg-gradient-to-r from-indigo-500 to-blue-500 text-white p-6 flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold">Selamat datang, {{ auth()->user()->name ?? 'Pembeli' }}!</h2>
                        <p class="mt-1 text-indigo-100">
                            Temukan produk terbaik, kelola pesananmu, dan ikuti penjual favorit.
                        </p>
                    </div>
                    <div class="hidden sm:flex gap-3">
                        <a href="#" class="px-4 py-2 border border-white/30 text-white rounded-md">Lihat Pesanan</a>
                    </div>
                </div>
            </section>

            {{-- Summary cards --}}
            <section class="mb-6">
                <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">

                    <!-- Indicators -->
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="0" class="active"></button>
                        <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="1"></button>
                        <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="2"></button>
                        <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="3"></button>
                    </div>

                    <!-- Slides -->
                    <div class="carousel-inner">

                        <div class="carousel-item active">
                            <div class="carousel-banner" style="background-image: url('{{ asset('images/carousel/slide1.png') }}');">
                            </div>
                        </div>

                        <div class="carousel-item">
                            <div class="carousel-banner" style="background-image: url('{{ asset('images/carousel/slide2.png') }}');">
                            </div>
                        </div>

                        <div class="carousel-item">
                            <div class="carousel-banner" style="background-image: url('{{ asset('images/carousel/slide3.png') }}');">
                            </div>
                        </div>

                        <div class="carousel-item">
                            <div class="carousel-banner" style="background-image: url('{{ asset('images/carousel/slide4.png') }}');">
                            </div>
                        </div>

                    </div>

                    <!-- Controls -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>

                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>

                </div>



            </section>

            <div class="grid grid-cols-12 gap-6" x-data="{filtersOpen:false}">
                <!-- Sidebar: Filters -->
                <aside class="col-span-12 lg:col-span-3">
                    <!-- Mobile slide-over -->
                    <div x-show="filtersOpen" x-cloak class="sm:hidden fixed inset-0 z-50 p-4 bg-white overflow-auto">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-semibold text-gray-700">Filter</h3>
                            <button @click="filtersOpen = false" class="text-gray-500">Tutup</button>
                        </div>

                        <form method="GET" action="{{ route('buyer.dashboard') }}" class="space-y-3 text-sm">
                            <div>
                                <label class="block text-gray-600">Kategori</label>
                                <select name="category" class="w-full mt-1 border-gray-200 rounded-md text-sm">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->name }}" {{ request('category') == $category->name ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-gray-600">Harga</label>
                                <div class="flex gap-2 mt-1">
                                    <input name="min_price" value="{{ request('min_price') }}" type="number" placeholder="Min" class="w-1/2 border-gray-200 rounded-md p-2 text-sm" />
                                    <input name="max_price" value="{{ request('max_price') }}" type="number" placeholder="Max" class="w-1/2 border-gray-200 rounded-md p-2 text-sm" />
                                </div>
                            </div>

                            <div>
                                <label class="block text-gray-600">Urutkan</label>
                                <select name="sort" class="w-full mt-1 border-gray-200 rounded-md text-sm">
                                    <option value="">Terbaru</option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
                                    <option value="popular">Paling Laris</option>
                                </select>
                            </div>

                            <button @click="filtersOpen = false" type="submit" class="mt-3 w-full bg-indigo-600 text-white rounded-md py-2">Terapkan</button>
                        </form>
                    </div>
                    <div class="hidden sm:block bg-white rounded-lg shadow p-4 lg:sticky lg:top-6">
                        <h3 class="font-semibold text-gray-700 mb-3">Filter</h3>
                        <form method="GET" action="{{ route('buyer.dashboard') }}" class="space-y-3 text-sm">
                            <div>
                                <label class="block text-gray-600">Kategori</label>
                                <select name="category" class="w-full mt-1 border-gray-200 rounded-md text-sm">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->name }}" {{ request('category') == $category->name ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-gray-600">Harga</label>
                                <div class="flex gap-2 mt-1">
                                    <input name="min_price" value="{{ request('min_price') }}" type="number" placeholder="Min" class="w-1/2 border-gray-200 rounded-md p-2 text-sm" />
                                    <input name="max_price" value="{{ request('max_price') }}" type="number" placeholder="Max" class="w-1/2 border-gray-200 rounded-md p-2 text-sm" />
                                </div>
                            </div>

                            <div>
                                <label class="block text-gray-600">Urutkan</label>
                                <select name="sort" class="w-full mt-1 border-gray-200 rounded-md text-sm">
                                    <option value="">Terbaru</option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
                                    <option value="popular">Paling Laris</option>
                                </select>
                            </div>

                            <button type="submit" class="mt-3 w-full bg-indigo-600 text-white rounded-md py-2">Terapkan</button>
                        </form>
                    </div>

                    <div class="mt-4 bg-white rounded-lg shadow p-4">
                        <h3 class="font-semibold text-gray-700 mb-3">Ringkasan Keranjang</h3>
                        <div class="text-sm text-gray-600">{{ $cartSummary['count'] ?? 0 }} item • Total: <strong class="text-gray-900">Rp{{ number_format($cartSummary['total'] ?? 0,0,',','.') }}</strong></div>
                        <a href="{{ route('buyer.cart') }}" class="mt-3 block text-center bg-green-500 text-white rounded-md py-2">Lihat Keranjang</a>
                    </div>
                </aside>

                <section class="col-span-12 lg:col-span-6">
                    <form method="GET" action="{{ route('buyer.search') }}" class="mb-4 flex items-center gap-4">
                        <div class="flex-1 relative">
                            <input name="q" value="{{ request('q') }}" type="search" placeholder="Cari produk, seller, kategori..." class="w-full border-gray-200 rounded-md p-3" />
                        </div>
                        <div class="flex gap-2">
                            <button type="button" @click="filtersOpen = !filtersOpen" class="px-4 py-2 bg-gray-50 border rounded-md sm:hidden">Filter</button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md">Cari</button>
                        </div>
                    </form>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse ($products as $product)
                        <div class="p-1">
                            <x-product-card :product="$product" />
                        </div>
                        @empty
                        <div class="text-sm text-gray-600">Tidak ada produk.</div>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $products->links() }}
                    </div>
                </section>

                <aside class="col-span-12 lg:col-span-3">
                    <div class="bg-white/95 rounded-lg shadow p-4 border-l-4 border-indigo-300">
                        <h3 class="font-semibold text-indigo-700 mb-3">Pesanan Terbaru</h3>
                        <ul class="space-y-3 text-sm text-gray-600">
                            @forelse($recentOrders as $order)
                            <li class="flex items-center justify-between">
                                <div>
                                    <div class="font-medium text-gray-800">#{{ $order->invoice_number }}</div>
                                    <div class="text-xs">Dikirim ke: {{ $order->shipping_address }}</div>
                                </div>
                                <div class="font-semibold {{ $order->status == 'Selesai' ? 'text-green-600' : ($order->status == 'Sedang dikirim' ? 'text-yellow-600' : 'text-gray-600') }}">{{ $order->status }}</div>
                            </li>
                            @empty
                            <li>Tidak ada pesanan terbaru.</li>
                            @endforelse
                        </ul>
                        <a href="{{ route('buyer.orders') }}" class="mt-3 block text-center text-indigo-600">Lihat semua pesanan</a>
                    </div>

                    <div class="mt-4 bg-white/95 rounded-lg shadow p-4 border-l-4 border-pink-300">
                        <h3 class="font-semibold text-pink-600 mb-3">Pesan</h3>
                        @if(count($recentMessages) > 0)
                        @foreach($recentMessages as $msg)
                        <div class="text-sm text-gray-600 mb-1">
                            <span class="font-medium text-gray-800">{{ $msg->sender_name }}:</span> {{ \Illuminate\Support\Str::limit($msg->content, 30) }}
                        </div>
                        @endforeach
                        @else
                        <div class="text-sm text-gray-600">Tidak ada pesan baru.</div>
                        @endif
                        <a href="{{ route('buyer.messages') }}" class="mt-3 block text-center text-pink-600">Buka pesan</a>
                    </div>

                    <div class="mt-4 bg-white/95 rounded-lg shadow p-4 border-l-4 border-yellow-300">
                        <h3 class="font-semibold text-yellow-700 mb-3">Penjual Direkomendasikan</h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            @forelse($recommendedSellers as $seller)
                            <li><a href="{{ route('shops.show', $seller) }}" class="font-medium text-indigo-700">{{ $seller->name }}</a> • ⭐ {{ number_format($shop->rating ?? 0,1) }} ★</li>

                            @empty
                            <li>Tidak ada penjual direkomendasikan.</li>
                            @endforelse
                        </ul>
                    </div>
                </aside>
            </div>
        </main>
    </div>
</x-app-layout>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
