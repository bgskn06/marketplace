@props(['item'])

<style>
    .product-thumb {
    width: 96px;
    height: 96px;
    border-radius: 12px;
    overflow: hidden;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}

</style>

<div data-item-id="{{ $item->id }}" class="reveal p-3 rounded-lg bg-white shadow-sm flex items-center gap-4 justify-between">
    <div class="flex-shrink-0">

        <div class="product-thumb">
            @php
                $photoPath = $item->product->mainPhoto?->path
                    ?? $item->product->photos->first()?->path
                    ?? null;
            @endphp

            @if ($photoPath)
                <img
                    src="{{ asset('storage/'.$photoPath) }}"
                    alt="{{ $item->product->name }}"
                    loading="lazy"
                >
            @else
                <span>No Image</span>
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
