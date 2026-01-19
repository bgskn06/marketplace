<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">{{ $product->name }}</h1>
                <div class="text-sm text-yellow-500 mt-1">{{ number_format($product->rating ?? 0, 1) }} ★</div>
            </div>
            <nav class="flex items-center gap-4 text-sm">
                <a href="{{ route('buyer.dashboard') }}" class="text-gray-700 hover:text-indigo-700">Home</a>
                <a href="{{ route('buyer.orders') }}" class="text-gray-700 hover:text-indigo-700">Orders</a>
                <a href="{{ route('buyer.messages') }}" class="text-gray-700 hover:text-indigo-700">Messages</a>
                <a href="{{ route('buyer.cart') }}" class="text-gray-700 hover:text-indigo-700">Cart <span
                        class="ml-1 inline-block bg-indigo-50 text-xs px-2 rounded-full text-indigo-700"
                        x-text="cartCount"></span></a>
                <a href="{{ route('buyer.profile') }}" class="text-gray-700 hover:text-indigo-700">Profile</a>
            </nav>
        </div>
    </x-slot>

    <main class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="w-full md:w-1/2">
                            <div class="bg-gray-100 rounded-md overflow-hidden">
                                @php
                                    // Prefer product->image column, fallback to photos
                                    if (!empty($product->image)) {
                                        $mainImg = \Illuminate\Support\Str::startsWith($product->image, [
                                            'http://',
                                            'https://',
                                        ])
                                            ? $product->image
                                            : asset('storage/' . $product->image);
                                    } else {
                                        $mainImg = $product->photos->first()?->path
                                            ? asset('storage/' . $product->photos->first()->path)
                                            : null;
                                    }
                                @endphp

                                @if ($mainImg)
                                    <img id="main-product-image" src="{{ $mainImg }}"
                                        class="w-full h-96 object-cover rounded-md" alt="{{ $product->name }}" />
                                @else
                                    <div class="w-full h-96 flex items-center justify-center text-gray-400">No image
                                    </div>
                                @endif

                                @if ($product->photos->count() > 1)
                                    <div class="mt-3 grid grid-cols-4 gap-2">
                                        @foreach ($product->photos as $photo)
                                            <button type="button"
                                                onclick="document.getElementById('main-product-image').src='{{ asset('storage/' . $photo->path) }}'">
                                                <img src="{{ asset('storage/' . $photo->path) }}"
                                                    class="w-full h-16 object-cover rounded-md" />
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="w-full md:w-1/2">
                            <h2 class="text-xl font-bold text-gray-800">{{ $product->name }}</h2>
                            <div class="text-sm text-gray-500">{{ $product->category }}</div>
                            <div class="mt-4 text-3xl font-extrabold text-indigo-700">
                                Rp{{ number_format($product->price ?? ($product->harga ?? 0), 0, ',', '.') }}</div>
                            <div class="mt-4 text-gray-700">{!! nl2br(e($product->description)) !!}</div>

                            <div class="mt-6">

                                <div class="flex flex-wrap items-center gap-3">

                                    {{-- 1. TOMBOL CHAT (Gaya 2: Product Context) --}}
                                    @auth
                                        @if (auth()->id() !== $product->user_id)
                                            {{-- Form untuk Trigger Chat Controller --}}
                                            <form action="{{ route('chat.start', $product->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="px-4 py-2 border border-indigo-600 text-indigo-600 rounded-md hover:bg-indigo-50 flex items-center gap-2 transition">
                                                    {{-- Ikon Chat SVG --}}
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.186.078-2.1 1.075-2.1 2.2v4.286c0 .197.03.388.077.57m0 0a5.026 5.026 0 0 1-1.097-.076 3.016 3.016 0 0 0-.75.408l-2.146 2.147a.5.5 0 0 1-.778-.363V15.93c-1.136-.092-1.98-1.057-1.98-2.193v-4.286c0-.97.616-1.813 1.5-2.097" />
                                                    </svg>
                                                    Chat Penjual
                                                </button>
                                            </form>
                                        @else
                                            {{-- Jika Pemilik Produk Melihat Produk Sendiri --}}
                                            <button disabled
                                                class="px-4 py-2 border border-gray-300 text-gray-400 rounded-md cursor-not-allowed flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                                                </svg>
                                                Produk Anda
                                            </button>
                                        @endif
                                    @else
                                        {{-- Jika Belum Login (Redirect ke Login) --}}
                                        <a href="{{ route('login') }}"
                                            class="px-4 py-2 border border-indigo-600 text-indigo-600 rounded-md hover:bg-indigo-50 flex items-center gap-2 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.186.078-2.1 1.075-2.1 2.2v4.286c0 .197.03.388.077.57m0 0a5.026 5.026 0 0 1-1.097-.076 3.016 3.016 0 0 0-.75.408l-2.146 2.147a.5.5 0 0 1-.778-.363V15.93c-1.136-.092-1.98-1.057-1.98-2.193v-4.286c0-.97.616-1.813 1.5-2.097" />
                                            </svg>
                                            Chat Login
                                        </a>
                                    @endauth

                                    {{-- 2. TOMBOL KERANJANG (Asli Anda) --}}
                                    <form method="POST" action="{{ route('buyer.cart.add') }}">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}" />
                                        <button type="submit"
                                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 shadow-sm flex items-center gap-2 transition">
                                            {{-- Ikon Keranjang --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                            </svg>
                                            + Keranjang
                                        </button>
                                    </form>

                                </div>

                            {{-- Reviews & Rating --}}
<div id="reviews" class="mt-6 bg-white rounded-lg shadow p-4">
                                <h3 class="font-semibold text-gray-800 mb-3">Ulasan Pembeli</h3>

                                <div class="mb-3">
                                    <div class="text-sm text-yellow-500">{{ number_format($product->rating ?? 0, 1) }} ★</div>
                                </div>

                                @if(session('error'))
                                    <div class="text-sm text-red-600 mb-2">{{ session('error') }}</div>
                                @endif
                                @if(session('success'))
                                    <div class="text-sm text-green-600 mb-2">{{ session('success') }}</div>
                                @endif

                                @auth
                                    @php
                                        $canRate = auth()->user()->orders()
                                            ->where(function($q){
                                                $q->whereRaw("LOWER(`status`) = 'delivered'")->orWhereRaw("LOWER(`status`) = 'selesai'");
                                            })
                                            ->whereHas('orderItems', function($q) use ($product){
                                                $q->where('product_id', $product->id);
                                            })->exists();

                                        $existingReview = $product->reviews()->where('user_id', auth()->id())->first();
                                    @endphp

                                    @if($canRate)
                                        <form method="POST" action="{{ route('buyer.products.reviews.store', $product) }}" class="space-y-2">
                                            @csrf
                                            <div>
                                                <label class="block text-sm text-gray-700">Nilai</label>
                                                <select name="rating" class="border-gray-200 rounded-md p-2 text-sm">
                                                    @for($i=5;$i>=1;$i--)
                                                        <option value="{{ $i }}" {{ (old('rating') ?? $existingReview->rating ?? '') == $i ? 'selected' : '' }}>{{ $i }} ★</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm text-gray-700">Ulasan (opsional)</label>
                                                <textarea name="comment" class="w-full border-gray-200 rounded-md p-2 text-sm" rows="3">{{ old('comment') ?? $existingReview->comment ?? '' }}</textarea>
                                            </div>
                                            <div>
                                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md">Kirim Ulasan</button>
                                            </div>
                                        </form>
                                    @else
                                        <div class="text-sm text-gray-600">Anda dapat memberi rating setelah pesanan diterima.</div>
                                    @endif
                                @else
                                    <div class="text-sm text-gray-600">Silakan <a href="{{ route('login') }}" class="text-indigo-600">login</a> untuk memberi rating.</div>
                                @endauth

                                <div class="mt-4 space-y-3">
                                    @forelse($product->reviews()->with('user')->latest()->get() as $review)
                                        <div class="border rounded p-3">
                                            <div class="text-sm font-medium text-gray-800">{{ $review->user->name ?? 'User' }} • <span class="text-yellow-500">{{ $review->rating }} ★</span></div>
                                            <div class="text-sm text-gray-600 mt-1">{{ $review->comment }}</div>
                                            <div class="text-xs text-gray-400 mt-1">{{ optional($review->created_at)->format('Y-m-d') }}</div>
                                        </div>
                                    @empty
                                        <div class="text-sm text-gray-600">Belum ada ulasan.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 bg-white rounded-lg shadow p-6">
                    <h3 class="font-semibold text-gray-800 mb-3">Detail Produk</h3>
                    <div class="text-sm text-gray-700">{!! nl2br(e($product->description)) !!}</div>
                </div>
            </div>

            <aside class="hidden lg:block">
                <div class="bg-white rounded-lg shadow p-4">
                    <h4 class="font-semibold text-gray-800">Penjual</h4>
                    <div class="text-sm text-gray-600 mt-2">{{ optional($product->shop)->name ?? 'Penjual Tidak Diketahui' }}</div>
                    @if($product->shop)
                        <a href="{{ route('shops.show', $product->shop) }}" class="mt-3 inline-block text-indigo-600">Lihat Toko</a>
                    @else
                        <span class="mt-3 inline-block text-gray-400">Lihat Toko</span>
                    @endif
                </div>

                <div class="mt-4 bg-white rounded-lg shadow p-4">
                    <h4 class="font-semibold text-gray-800">Informasi Pembelian</h4>
                    <div class="text-sm text-gray-600 mt-2">Garansi, pengembalian, ongkos kirim, dsb.</div>
                </div>
            </aside>
        </div>
    </main>
</x-app-layout>
