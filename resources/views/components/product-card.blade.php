@props(['product'])

<article
    onclick="if (event.target.closest('button') || event.target.closest('a')) return; window.location='{{ route('products.show', $product->id) }}'"
    class="bg-white rounded-xl shadow hover:shadow-xl transition transform hover:-translate-y-1 cursor-pointer group flex flex-col h-full"
>
    {{-- Image --}}
    <div class="w-full aspect-square bg-gray-100 overflow-hidden">
        @php
            $photoPath = $product->mainPhoto?->path
                ?? $product->photos->first()?->path
                ?? null;
        @endphp

        @if ($photoPath)
            <img
                src="{{ asset('storage/'.$photoPath) }}"
                alt="{{ $product->name }}"
                class="w-full h-full object-cover transition duration-300 group-hover:scale-105"
            >
        @else
            <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">
                No Image
            </div>
        @endif
    </div>

    {{-- Content --}}
    <div class="p-4 flex flex-col flex-1">
        <div class="flex items-start justify-between gap-3">
            <div class="min-w-0">
                <h2 class="text-lg font-semibold text-gray-800 truncate line-clamp-2" title="{{ $product->name ?? $product->title ?? 'Produk' }}">
                    {{ $product->name ?? $product->title ?? 'Produk tidak ditemukan' }}
                </h2>
                <div class="flex items-center gap-2 mt-2 text-sm">
                    <span class="text-sm text-gray-500 truncate">{{ $product->category ?? '—' }}</span>
                    <span class="inline-flex items-center gap-1 bg-yellow-50 text-yellow-700 px-2 py-0.5 rounded text-sm">⭐ {{ number_format($product->rating ?? 0,1) }}</span>
                </div>
            </div>

            <div class="text-right flex-shrink-0 ml-2">
                <div class="text-indigo-600 font-bold text-lg">Rp{{ number_format($product->price ?? $product->harga ?? 0,0,',','.') }}</div>
                <div class="text-xs text-gray-400">Stok: {{ $product->stock }}</div>
            </div>
        </div>

        <p class="text-sm text-gray-600 mt-3 mb-3 line-clamp-2 min-h-[48px]">{{ \Illuminate\Support\Str::limit($product->description, 100) }}</p>

        <div class="mt-2 flex items-center justify-between">
            <a href="{{ route('products.show', $product->id) }}" class="px-3 py-1 text-sm border rounded-md text-indigo-600 hover:bg-gray-50 whitespace-nowrap">Detail</a>
            <form method="POST" action="{{ route('buyer.cart.add') }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}" />
                <div class="flex items-center gap-3">
                    <button type="submit" class="px-3 py-1 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition whitespace-nowrap">Tambah</button>
                </div>
            </form>
        </div>
        
    </div>
</article>
