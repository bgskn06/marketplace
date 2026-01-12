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
                <a href="{{ route('buyer.cart') }}" class="text-gray-700 hover:text-indigo-700">Cart <span class="ml-1 inline-block bg-indigo-50 text-xs px-2 rounded-full text-indigo-700" x-text="cartCount"></span></a>
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
                                        $mainImg = \Illuminate\Support\Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/'.$product->image);
                                    } else {
                                        $mainImg = $product->photos->first()?->path ? asset('storage/'.$product->photos->first()->path) : null;
                                    }
                                @endphp

                                @if($mainImg)
                                    <img id="main-product-image" src="{{ $mainImg }}" class="w-full h-96 object-cover rounded-md" alt="{{ $product->name }}" />
                                @else
                                    <div class="w-full h-96 flex items-center justify-center text-gray-400">No image</div>
                                @endif

                                @if($product->photos->count() > 1)
                                    <div class="mt-3 grid grid-cols-4 gap-2">
                                        @foreach($product->photos as $photo)
                                            <button type="button" onclick="document.getElementById('main-product-image').src='{{ asset('storage/'.$photo->path) }}'">
                                                <img src="{{ asset('storage/'.$photo->path) }}" class="w-full h-16 object-cover rounded-md" />
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="w-full md:w-1/2">
                            <h2 class="text-xl font-bold text-gray-800">{{ $product->name }}</h2>
                            <div class="text-sm text-gray-500">{{ $product->category }}</div>
                            <div class="mt-4 text-3xl font-extrabold text-indigo-700">Rp{{ number_format($product->price ?? $product->harga ?? 0,0,',','.') }}</div>
                            <div class="mt-4 text-gray-700">{!! nl2br(e($product->description)) !!}</div>

                            <form method="POST" action="{{ route('buyer.cart.add') }}" class="mt-6">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}" />
                                <div class="flex items-center gap-3">
                                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md">Tambah ke Keranjang</button>
                                    <div class="text-sm text-gray-500">Stok: <strong>{{ $product->stock }}</strong></div>
                                </div>
                            </form>

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
                    <a href="#" class="mt-3 inline-block text-indigo-600">Lihat Toko</a>
                </div>

                <div class="mt-4 bg-white rounded-lg shadow p-4">
                    <h4 class="font-semibold text-gray-800">Informasi Pembelian</h4>
                    <div class="text-sm text-gray-600 mt-2">Garansi, pengembalian, ongkos kirim, dsb.</div>
                </div>
            </aside>
        </div>
    </main>
</x-app-layout>
