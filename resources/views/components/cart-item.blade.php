@props(['item'])

<div data-item-id="{{ $item->id }}" class="reveal p-3 rounded-lg bg-white shadow-sm flex items-center gap-4 justify-between">
    <div class="flex items-center gap-4">
        <div class="w-full h-44 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center">
            @php
                $photoPath = $item->product->mainPhoto?->path
                    ?? $item->product->photos->first()?->path
                    ?? null;
            @endphp

            @if ($photoPath)
                <img
                    src="{{ asset('storage/'.$photoPath) }}"
                    alt="{{ $item->product->name }}"
                    class="w-full h-full object-cover transition duration-300 group-hover:scale-105"
                >
            @else
                <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">
                    No Image
                </div>
            @endif
        </div>

        <div class="min-w-0">
            <div class="font-medium text-gray-800 truncate">{{ optional($item->product)->name ?? optional($item->product)->title ?? 'Produk tidak ditemukan' }}</div>
            <div class="text-sm text-gray-500">Rp{{ number_format(optional($item->product)->price ?? optional($item->product)->harga ?? 0,0,',','.') }}</div>
        </div>
    </div>

    <div class="flex items-center gap-4">
        <div class="flex items-center border rounded-md overflow-hidden">
            <button data-item-id="{{ $item->id }}" class="px-3 py-1 text-sm bg-gray-50 qty-decrease">-</button>
            <div class="px-3 text-sm qty-number">{{ $item->quantity }}</div>
            <button data-product-id="{{ optional($item->product)->id }}" data-item-id="{{ $item->id }}" class="px-3 py-1 text-sm bg-gray-50 qty-increase">+</button>
        </div>

        <div class="text-right">
            <div class="font-semibold text-indigo-700 item-total" data-price="{{ optional($item->product)->price ?? optional($item->product)->harga ?? 0 }}">Rp{{ number_format((optional($item->product)->price ?? optional($item->product)->harga ?? 0) * $item->quantity,0,',','.') }}</div>
            <button data-item-id="{{ $item->id }}" class="text-sm text-red-600 remove-from-cart">Hapus</button>
        </div>
    </div>
</div>
