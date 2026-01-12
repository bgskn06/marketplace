<article
    onclick="if (event.target.closest('button') || event.target.closest('a')) return; window.location='{{ route('products.show', $product->id) }}'"
    class="bg-white rounded-xl shadow hover:shadow-xl transition transform hover:-translate-y-1 cursor-pointer group flex flex-col h-full"
>

    {{-- Image --}}
    <div class="w-full h-44 bg-gray-100 rounded-t-xl overflow-hidden">
        @if(!empty($product->image))
            <img
                src="{{ asset('storage/'.$product->image) }}"
                alt="{{ $product->name }}"
                class="w-full h-full object-cover group-hover:scale-105 transition"
            >
        @else
            <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">
                No Image
            </div>
        @endif
    </div>

    {{-- Content --}}
    <div class="p-4 flex flex-col flex-1">

        {{-- Title --}}
        <h2 class="text-sm font-semibold text-gray-800 line-clamp-2 min-h-[40px]">
            {{ $product->name ?? $product->title ?? 'Produk tidak ditemukan' }}
        </h2>

        {{-- Category & Rating --}}
        <div class="flex items-center justify-between mt-2 text-xs">
            <span class="text-gray-500 truncate">
                {{ $product->category ?? '—' }}
            </span>

            <span class="inline-flex items-center gap-1 bg-yellow-50 text-yellow-700 px-2 py-0.5 rounded">
                ⭐ {{ number_format($product->rating ?? 0, 1) }}
            </span>
        </div>

        {{-- Description --}}
        <p class="text-xs text-gray-600 mt-2 line-clamp-2 min-h-[32px]">
            {{ \Illuminate\Support\Str::limit($product->description, 80) }}
        </p>

        {{-- Price --}}
        <div class="mt-3 text-indigo-600 font-bold text-base">
            Rp{{ number_format($product->price ?? 0, 0, ',', '.') }}
        </div>

        {{-- Stock --}}
        <div class="text-xs text-gray-400 mb-3">
            Stok: {{ $product->stock ?? 0 }}
        </div>

        {{-- Spacer --}}
        <div class="flex-1"></div>

        {{-- Actions --}}
        <div class="flex gap-2">
            <a
                href="{{ route('products.show', $product->id) }}"
                class="flex-1 text-center px-3 py-2 text-sm border rounded-md text-indigo-600 hover:bg-gray-50"
            >
                Detail
            </a>

            <button
                data-product-id="{{ $product->id }}"
                class="flex-1 px-3 py-2 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition"
            >
                Tambah
            </button>
        </div>
    </div>
</article>
