@props(['order'])

<div data-reveal class="card mb-3 shadow-sm border-0 reveal" style="transition: box-shadow .2s ease;" onmouseenter="this.classList.add('shadow')" onmouseleave="this.classList.remove('shadow')">
    <div class="card-body d-flex flex-column gap-2">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="fw-semibold text-dark">
                    {{ $order->order_number ?? ('#INV-' . $order->id) }}
                </div>
                <div class="text-muted small">
                    Tanggal: {{ optional($order->created_at)->format('Y-m-d') }}
                </div>
            </div>

            <div class="small text-secondary">
                Total:
                <strong class="text-dark">
                    Rp{{ number_format($order->total,0,',','.') }}
                </strong>
            </div>
        </div>

        {{-- STATUS --}}
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <span class="badge rounded-pill
                    {{ strtolower($order->status) === 'delivered'
                        ? 'bg-success-subtle text-success'
                        : 'bg-warning-subtle text-warning' }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            <div class="text-muted small">
                #{{ $order->id }}
            </div>
        </div>

        {{-- ORDER ITEMS --}}
        <div class="mt-3 d-flex flex-column gap-2">
            @foreach($order->orderItems as $item)
            @php $product = $item->product; @endphp

            <div class="border rounded p-3 d-flex justify-content-between align-items-start flex-wrap gap-2">

                {{-- PRODUCT INFO --}}
                <div>
                    <div class="fw-semibold text-dark">
                        {{ $product->name ?? 'Produk' }}
                    </div>
                    <div class="text-muted small">
                        Qty: {{ $item->quantity }}
                    </div>

                    @if($item->shipped_at)
                    <div class="text-success small">
                        Dikirim: {{ $item->shipped_at->format('Y-m-d') }}
                        @if($item->tracking_number)
                        - Resi: {{ $item->tracking_number }}
                        @endif
                    </div>
                    @endif
                </div>

                {{-- ACTIONS --}}
                <div class="text-end">
                    @php
                    $canReview = in_array(strtolower($order->status), ['delivered', 'selesai']);
                    $alreadyReviewed = $product
                    ? $product->reviews()->where('user_id', auth()->id())->exists()
                    : false;
                    @endphp

                    @if($product && $canReview && !$alreadyReviewed)
                    <a href="{{ route('products.show', $product) }}#reviews" class="btn btn-sm btn-primary mb-1">
                        Beri Ulasan
                    </a>
                    @elseif($product && $canReview && $alreadyReviewed)
                    <a href="{{ route('products.show', $product) }}#reviews" class="btn btn-sm btn-outline-secondary mb-1">
                        Lihat Ulasan
                    </a>
                    @else
                    <div class="text-muted small">
                        Belum bisa memberi ulasan
                    </div>
                    @endif

                    @if($order->payment_method === 'bank_transfer'
                    && $order->status === \App\Models\Order::STATUS_UNPAID)
                    <div class="mt-2">
                        <a href="{{ route('buyer.orders.payment', $order) }}" class="btn btn-sm btn-warning text-white">
                            Lihat Pembayaran
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

    </div>
</div>

