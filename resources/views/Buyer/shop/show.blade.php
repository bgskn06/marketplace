<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Toko — {{ $shop->name }}</h1>
                <div class="text-sm text-yellow-500 mt-1">⭐ {{ number_format($shop->rating ?? 0, 1) }}</div>
            </div>

            <nav class="flex items-center gap-4 text-sm">
                <a href="{{ route('buyer.dashboard') }}" class="text-gray-700 hover:text-indigo-700">Home</a>
                <a href="{{ route('buyer.orders') }}" class="text-gray-700 hover:text-indigo-700">Orders</a>
                <a href="{{ route('buyer.messages') }}" class="text-gray-700 hover:text-indigo-700">Messages</a>
                <a href="{{ route('buyer.cart') }}" class="text-gray-700 hover:text-indigo-700">Cart</a>
                <a href="{{ route('buyer.profile') }}" class="text-gray-700 hover:text-indigo-700">Profile</a>
            </nav>
        </div>
    </x-slot>

    <main class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow p-6 flex gap-6 items-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-md overflow-hidden flex items-center justify-center">
                        @if($shop->logo)
                            <img src="{{ asset('storage/'.$shop->logo) }}" class="w-full h-full object-cover" alt="{{ $shop->name }}" />
                        @else
                            <div class="text-gray-400">No Logo</div>
                        @endif
                    </div>

                    <div>
                        <h2 class="text-xl font-bold text-gray-800">{{ $shop->name }}</h2>
                        @if($shop->user)
                        <div class="text-sm text-gray-600">Penjual: {{ $shop->user->name }}</div>
                        @endif
                        <div class="mt-2 text-sm text-gray-600">{{ $shop->address ?? 'Alamat tidak tersedia' }}</div>

                        <div class="mt-3 flex items-center gap-2">
                            @auth
                                @if($isFollowing)
                                    <form method="POST" action="{{ route('shops.unfollow', $shop) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-2 bg-gray-200 rounded-md text-sm">Berhenti Mengikuti</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('shops.follow', $shop) }}">
                                        @csrf
                                        <button class="px-3 py-2 bg-indigo-600 text-white rounded-md text-sm">Follow</button>
                                    </form>
                                @endif

                                <a href="{{ route('seller.chat', $shop->user) }}" class="px-3 py-2 border rounded-md text-sm">Hubungi Penjual</a>
                            @else
                                <a href="{{ route('login') }}" class="px-3 py-2 bg-indigo-600 text-white rounded-md text-sm">Login untuk Follow</a>
                                <a href="{{ route('login') }}" class="px-3 py-2 border rounded-md text-sm">Login untuk Hubungi</a>
                            @endauth
                        </div>
                    </div>
                </div>

                <div class="mt-6 bg-white rounded-lg shadow p-6">
                    <h3 class="font-semibold text-gray-800 mb-3">Produk dari {{ $shop->name }}</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($products as $product)
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
                </div>
                {{-- Shop Reviews --}}
                <div class="mt-6 bg-white rounded-lg shadow p-6">
                    <h3 class="font-semibold text-gray-800 mb-3">Ulasan Toko</h3>

                    <div class="text-sm text-yellow-500 mb-3">Rating saat ini: {{ number_format($shop->rating ?? 0,1) }} ★</div>

                    @if(session('error'))
                        <div class="text-sm text-red-600 mb-2">{{ session('error') }}</div>
                    @endif
                    @if(session('success'))
                        <div class="text-sm text-green-600 mb-2">{{ session('success') }}</div>
                    @endif

                    @auth
                        @if($canReview)
                            @php $existing = $shop->reviews()->where('user_id', auth()->id())->first(); @endphp
                            <form method="POST" action="{{ route('shops.reviews.store', $shop) }}" class="space-y-2">
                                @csrf
                                <div>
                                    <label class="block text-sm text-gray-700">Nilai</label>
                                    <select name="rating" class="border-gray-200 rounded-md p-2 text-sm">
                                        @for($i=5;$i>=1;$i--)
                                            <option value="{{ $i }}" {{ (old('rating') ?? $existing->rating ?? '') == $i ? 'selected' : '' }}>{{ $i }} ★</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-700">Ulasan (opsional)</label>
                                    <textarea name="comment" class="w-full border-gray-200 rounded-md p-2 text-sm" rows="3">{{ old('comment') ?? $existing->comment ?? '' }}</textarea>
                                </div>
                                <div>
                                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md">Kirim Ulasan</button>
                                </div>
                            </form>
                        @else
                            <div class="text-sm text-gray-600">Anda dapat memberi ulasan setelah menerima pesanan dari toko ini.</div>
                        @endif
                    @else
                        <div class="text-sm text-gray-600">Silakan <a href="{{ route('login') }}" class="text-indigo-600">login</a> untuk memberi ulasan.</div>
                    @endauth

                    <div class="mt-4 space-y-3">
                        @forelse($shopReviews as $review)
                            <div class="border rounded p-3">
                                <div class="text-sm font-medium text-gray-800">{{ $review->user->name ?? 'User' }} • <span class="text-yellow-500">{{ $review->rating }} ★</span></div>
                                <div class="text-sm text-gray-600 mt-1">{{ $review->comment }}</div>
                                <div class="text-xs text-gray-400 mt-1">{{ optional($review->created_at)->format('Y-m-d') }}</div>
                            </div>
                        @empty
                            <div class="text-sm text-gray-600">Belum ada ulasan untuk toko ini.</div>
                        @endforelse
                    </div>
                </div>            </div>

            <aside class="hidden lg:block">
                <div class="bg-white rounded-lg shadow p-4">
                    <h4 class="font-semibold text-gray-800">Tentang Toko</h4>
                    <div class="text-sm text-gray-600 mt-2">Informasi singkat mengenai toko, kebijakan, dsb.</div>
                </div>

                <div class="mt-4 bg-white rounded-lg shadow p-4">
                    <h4 class="font-semibold text-gray-800">Rating & Statistik</h4>
                    <div class="text-sm text-gray-600 mt-2">Rating rata-rata: <strong class="text-indigo-700">{{ number_format($shop->rating ?? 0,1) }}</strong></div>
                    <div class="text-sm text-gray-600">Jumlah produk: <strong>{{ $shop->products()->count() }}</strong></div>
                </div>
            </aside>
        </div>
    </main>
</x-app-layout>
