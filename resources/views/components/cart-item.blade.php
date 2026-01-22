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
    }

    @media (max-width: 576px) {
        .product-thumb {
            width: 72px;
            height: 72px;
        }
    }

</style>

<div data-item-id="{{ $item->id }}" class="card shadow-sm mb-3">
    <div class="card-body">

        <div class="row g-3 align-items-center">

            <!-- PRODUCT IMAGE -->
            <div class="col-auto">
                <div class="product-thumb">
                    @php
                    $photoPath = $item->product->mainPhoto?->path
                    ?? $item->product->photos->first()?->path
                    ?? null;
                    @endphp

                    @if ($photoPath)
                    <img src="{{ asset('storage/'.$photoPath) }}" alt="{{ $item->product->name }}" loading="lazy">
                    @else
                    <small class="text-muted">No Image</small>
                    @endif
                </div>
            </div>

            <!-- PRODUCT INFO -->
            <div class="col">
                <div class="fw-semibold text-truncate">
                    {{ optional($item->product)->name ?? optional($item->product)->title ?? 'Produk tidak ditemukan' }}
                </div>
                <div class="text-muted small">
                    Rp{{ number_format(optional($item->product)->price ?? optional($item->product)->harga ?? 0,0,',','.') }}
                </div>
            </div>

            <!-- ACTIONS -->
            <div class="col-12 col-md-auto">
                <div class="d-flex align-items-center justify-content-between gap-3">

                    <!-- QTY -->
                    <div class="input-group input-group-sm" style="width:120px">
                        <button class="btn btn-outline-secondary qty-decrease" data-item-id="{{ $item->id }}">
                            −
                        </button>

                        <span class="input-group-text qty-number bg-white">
                            {{ $item->quantity }}
                        </span>

                        <button class="btn btn-outline-secondary qty-increase" data-product-id="{{ optional($item->product)->id }}" data-item-id="{{ $item->id }}">
                            +
                        </button>
                    </div>

                    <!-- PRICE & REMOVE -->
                    <div class="text-end">
                        <div class="fw-bold text-primary item-total" data-price="{{ optional($item->product)->price ?? optional($item->product)->harga ?? 0 }}">
                            Rp{{ number_format((optional($item->product)->price ?? optional($item->product)->harga ?? 0) * $item->quantity,0,',','.') }}
                        </div>

                        <button class="btn btn-link text-danger p-0 small remove-from-cart" data-item-id="{{ $item->id }}">
                            Hapus
                        </button>
                    </div>

                </div>
            </div>

        </div>

    </div>
</div>

