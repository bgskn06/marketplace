@props(['product'])

<article
    onclick="if (event.target.closest('button') || event.target.closest('a')) return; window.location='{{ route('products.show', $product->id) }}'"
    class="bg-white rounded-xl shadow hover:shadow-xl transition transform hover:-translate-y-1 cursor-pointer group flex flex-col h-full"
>
    <div class="w-full h-44 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center">
        @php
            $photoPath = $product->mainPhoto?->path
                ?? $product->photos->first()?->path
                ?? null;
        @endphp

        @if ($photoPath)
            <img
                src="{{ asset('storage/'.$photoPath) }}"
                alt="{{ $product->name }}"
                class="w-full h-full object-contain transition duration-300 group-hover:scale-105"
            >

        @else
            <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">
                No Image
            </div>
        @endif
    </div>

    {{-- CONTENT --}}
    <div class="p-4 flex flex-col flex-1">

        {{-- TITLE --}}
        <h2
            class="text-sm font-semibold text-gray-800 leading-snug line-clamp-2 min-h-[40px]"
            title="{{ $product->name ?? $product->title ?? 'Produk' }}"
        >
            {{ $product->name ?? $product->title ?? 'Produk tidak ditemukan' }}
        </h2>

        {{-- CATEGORY + RATING --}}
        <div class="flex items-center gap-2 mt-2 text-xs min-h-[20px]">
            <span class="text-gray-500 truncate">
                {{ $product->category ?? '—' }}
            </span>
            <span class="inline-flex items-center gap-1 bg-yellow-50 text-yellow-700 px-2 py-0.5 rounded">
                ⭐ {{ number_format($product->rating ?? 0,1) }}
            </span>
        </div>

        {{-- DESCRIPTION --}}
        <p class="text-xs text-gray-600 mt-3 line-clamp-2 min-h-[32px]">
            {{ \Illuminate\Support\Str::limit($product->description, 100) }}
        </p>

        {{-- PRICE --}}
        <div class="mt-4 text-indigo-600 font-bold text-lg">
            Rp{{ number_format($product->price ?? $product->harga ?? 0,0,',','.') }}
        </div>

        {{-- STOCK --}}
        <div class="text-xs text-gray-400 mb-3">
            Stok: {{ $product->stock }}
        </div>

        {{-- ACTIONS --}}
        <div class="mt-auto flex items-center justify-between gap-2">
            <a
                href="{{ route('products.show', $product->id) }}"
                class="flex-1 text-center px-3 py-1.5 text-xs border rounded-md text-indigo-600 hover:bg-gray-50 whitespace-nowrap"
            >
                Detail
            </a>

            <form method="POST" action="{{ route('buyer.cart.add') }}" class="flex-1">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}" />
                <button
                    type="submit"
                    class="w-full px-3 py-1.5 text-xs bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition whitespace-nowrap"
                >
                    Tambah
                </button>
            </form>
        </div>

    </div>
</article>
