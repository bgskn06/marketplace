@props(['product'])

<article
    onclick="if (event.target.closest('button') || event.target.closest('a')) return; window.location='{{ route('products.show', $product->id) }}'"
    class="card h-100 shadow-sm border-0 product-card cursor-pointer"
    style="transition: all .2s ease;"
    onmouseenter="this.classList.add('shadow')"
    onmouseleave="this.classList.remove('shadow')"
>
    {{-- IMAGE --}}
    <div class="bg-light d-flex align-items-center justify-content-center rounded-top overflow-hidden"
         style="height: 180px;">
        @php
            $photoPath = $product->mainPhoto?->path
                ?? $product->photos->first()?->path
                ?? null;
        @endphp

        @if ($photoPath)
            <img
                src="{{ asset('storage/'.$photoPath) }}"
                alt="{{ $product->name }}"
                class="img-fluid"
                style="max-height: 100%; object-fit: contain; transition: transform .3s;"
                onmouseenter="this.style.transform='scale(1.05)'"
                onmouseleave="this.style.transform='scale(1)'"
            >
        @else
            <div class="text-muted small">No Image</div>
        @endif
    </div>

    {{-- CONTENT --}}
    <div class="card-body d-flex flex-column">

        {{-- TITLE --}}
        <h6
            class="fw-semibold text-dark mb-1"
            style="min-height:40px; line-height:1.3;"
            title="{{ $product->name ?? $product->title ?? 'Produk' }}"
        >
            {{ $product->name ?? $product->title ?? 'Produk tidak ditemukan' }}
        </h6>

        {{-- CATEGORY + RATING --}}
        <div class="d-flex align-items-center gap-2 mb-2 small text-muted">
            <span class="text-truncate">
                {{ is_object($product->category) ? $product->category->name : ($product->category ?? ($category_id?->name ?? '-')) }}
            </span>

            <span class="badge bg-warning-subtle text-warning fw-semibold">
                ⭐ {{ number_format($product->rating ?? 0,1) }}
            </span>
        </div>

        {{-- DESCRIPTION --}}
        <p class="text-muted small mb-2" style="min-height:32px;">
            {{ \Illuminate\Support\Str::limit($product->description, 100) }}
        </p>

        {{-- PRICE --}}
        <div class="fw-bold text-primary fs-5 mb-1">
            Rp{{ number_format($product->price ?? $product->harga ?? 0,0,',','.') }}
        </div>

        {{-- STOCK --}}
        <div class="text-muted small mb-3">
            Stok: {{ $product->stock }}
        </div>

        {{-- ACTIONS --}}
        <div class="mt-auto d-flex gap-2">
            <a
                href="{{ route('products.show', $product->id) }}"
                class="btn btn-outline-primary btn-sm w-100 text-nowrap"
            >
                Detail
            </a>

            <form method="POST" action="{{ route('buyer.cart.add') }}" class="w-100">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}" />
                <button
                    type="submit"
                    class="btn btn-primary btn-sm w-100"
                    aria-label="Tambah ke keranjang"
                >
                    Tambah
                </button>
            </form>
        </div>

    </div>
</article>
