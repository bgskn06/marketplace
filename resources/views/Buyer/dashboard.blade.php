<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900">
                Marketplace — Buyer Dashboard
            </h1>

            <nav class="flex items-center gap-4 text-sm">
                <a href="{{ route('buyer.dashboard') }}" class="text-gray-700 hover:text-indigo-700">Home</a>
                <a href="{{ route('buyer.orders') }}" class="text-gray-700 hover:text-indigo-700">Orders</a>
                <a href="{{ route('buyer.messages') }}" class="text-gray-700 hover:text-indigo-700">Messages</a>
                <a href="{{ route('buyer.cart') }}" class="text-gray-700 hover:text-indigo-700">Cart <span class="ml-1 inline-block bg-indigo-50 text-xs px-2 rounded-full text-indigo-700" x-text="cartCount"></span></a>
                <a href="{{ route('buyer.profile') }}" class="text-gray-700 hover:text-indigo-700">Profile</a>
            </nav>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-indigo-50 via-white to-pink-50" x-data="{ filtersOpen:false, cartCount: {{ $summary['cart_count'] ?? 0 }} }">
        <main class="max-w-7xl` mx-auto p-4 sm:p-6 lg:p-8">

            {{-- Banner --}}
            <section class="mb-6">
                <div class="rounded-lg bg-gradient-to-r from-indigo-500 to-blue-500 text-white p-6 flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold">Selamat datang, Pembeli!</h2>
                        <p class="mt-1 text-indigo-100">
                            Temukan produk terbaik, kelola pesananmu, dan ikuti penjual favorit.
                        </p>
                    </div>
                    <div class="hidden sm:flex gap-3">
                      <a href="#" class="px-4 py-2 btn-animated text-white rounded-md font-medium">Jelajahi Produk</a>
                      <a href="#" class="px-4 py-2 border border-white/30 text-white rounded-md">Lihat Pesanan</a>
                    </div>
                </div>
            </section>

            {{-- Summary cards --}}
            <section class="mb-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="text-sm text-gray-500">Keranjang</div>
                        <div class="mt-2 text-2xl font-bold text-indigo-700">{{ $summary['cart_count'] ?? 0 }}</div>
                        <div class="mt-2 text-xs text-gray-500">Items dalam keranjang</div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="text-sm text-gray-500">Pesanan</div>
                        <div class="mt-2 text-2xl font-bold text-indigo-700">{{ $summary['orders_count'] ?? 0 }}</div>
                        <div class="mt-2 text-xs text-gray-500">Pesanan yang dibuat</div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="text-sm text-gray-500">Produk</div>
                        <div class="mt-2 text-2xl font-bold text-indigo-700">{{ $stats['product_count'] ?? 0 }}</div>
                        <div class="mt-2 text-xs text-gray-500">Total produk tersedia</div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="text-sm text-gray-500">Top Rated</div>
                        <div class="mt-2 text-2xl font-bold text-indigo-700">{{ $stats['top_rated_count'] ?? 0 }}</div>
                        <div class="mt-2 text-xs text-gray-500">Produk rating ≥ 4.5</div>
                    </div>
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
                            <option value="Elektronik" {{ request('category') == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                            <option value="Fashion" {{ request('category') == 'Fashion' ? 'selected' : '' }}>Fashion</option>
                            <option value="Rumah & Dapur" {{ request('category') == 'Rumah & Dapur' ? 'selected' : '' }}>Rumah & Dapur</option>
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
                            <option value="Elektronik" {{ request('category') == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                            <option value="Fashion" {{ request('category') == 'Fashion' ? 'selected' : '' }}>Fashion</option>
                            <option value="Rumah & Dapur" {{ request('category') == 'Rumah & Dapur' ? 'selected' : '' }}>Rumah & Dapur</option>
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
                    <div class="text-sm text-gray-600">0 item • Total: <strong class="text-gray-900">Rp0</strong></div>
                    <a href="#" class="mt-3 block text-center bg-green-500 text-white rounded-md py-2">Lihat Keranjang</a>
                    </div>
                </aside>

                <section class="col-span-12 lg:col-span-6">
                    <form method="GET" action="{{ route('buyer.dashboard') }}" class="mb-4 flex items-center gap-4">
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
                        <li class="flex items-center justify-between">
                        <div>
                            <div class="font-medium text-gray-800">#INV-1023</div>
                            <div class="text-xs">Dikirim ke: Jl. Contoh No.1</div>
                        </div>
                        <div class="text-green-600 font-semibold">Selesai</div>
                        </li>
                        <li class="flex items-center justify-between">
                        <div>
                            <div class="font-medium text-gray-800">#INV-1022</div>
                            <div class="text-xs">Dikirim ke: Jl. Contoh No.2</div>
                        </div>
                        <div class="text-yellow-600 font-semibold">Sedang dikirim</div>
                        </li>
                    </ul>
                    <a href="#" class="mt-3 block text-center text-indigo-600">Lihat semua pesanan</a>
                    </div>

                    <div class="mt-4 bg-white/95 rounded-lg shadow p-4 border-l-4 border-pink-300">
                    <h3 class="font-semibold text-pink-600 mb-3">Pesan</h3>
                    <div class="text-sm text-gray-600">Tidak ada pesan baru.</div>
                    <a href="#" class="mt-3 block text-center text-pink-600">Buka pesan</a>
                    </div>

                    <div class="mt-4 bg-white/95 rounded-lg shadow p-4 border-l-4 border-yellow-300">
                    <h3 class="font-semibold text-yellow-700 mb-3">Penjual Direkomendasikan</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>Penjual A • ⭐ 4.8</li>
                        <li>Penjual B • ⭐ 4.7</li>
                        <li>Penjual C • ⭐ 4.6</li>
                    </ul>
                    </div>
                </aside>
                </div>
        </main>
    </div>
</x-app-layout>
