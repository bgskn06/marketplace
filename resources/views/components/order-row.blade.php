@props(['order'])

<div data-reveal class="reveal flex flex-col gap-2 p-4 rounded-lg bg-white shadow-sm hover:shadow-md transition">
    <div class="flex items-start justify-between">
        <div>
            <div class="font-medium text-gray-800">{{ $order->order_number ?? ('#INV-' . $order->id) }}</div>
            <div class="text-sm text-gray-500">Tanggal: {{ optional($order->created_at)->format('Y-m-d') }}</div>
        </div>
        <div class="text-sm text-gray-700">Total: <strong class="text-gray-900">Rp{{ number_format($order->total,0,',','.') }}</strong></div>
    </div>

    <div class="flex items-center justify-between">
        <div class="text-sm">
            <span class="px-3 py-1 rounded-full text-sm {{ strtolower($order->status) === 'delivered' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">{{ ucfirst($order->status) }}</span>
        </div>
        <div class="text-xs text-gray-400">#{{ $order->id }}</div>
    </div>

    {{-- Order items --}}
    <div class="mt-3 space-y-2">
        @foreach($order->orderItems as $item)
        @php $product = $item->product; @endphp
        <div class="flex items-center justify-between border rounded p-3">
            <div>
                <div class="font-medium text-gray-800">{{ $product->name ?? 'Produk' }}</div>
                <div class="text-xs text-gray-500">Qty: {{ $item->quantity }}</div>
                @if($item->shipped_at)
                <div class="text-xs text-green-700">Dikirim: {{ $item->shipped_at->format('Y-m-d') }} @if($item->tracking_number) - Resi: {{ $item->tracking_number }} @endif</div>
                @endif
            </div>

            <div>
                @php
                $canReview = in_array(strtolower($order->status), ['delivered', 'selesai']);
                $alreadyReviewed = $product ? $product->reviews()->where('user_id', auth()->id())->exists() : false;
                @endphp

                @if($product && $canReview && !$alreadyReviewed)
                <a href="{{ route('products.show', $product) }}#reviews" class="px-3 py-2 bg-indigo-600 text-white rounded-md text-sm">Beri Ulasan</a>
                @elseif($product && $canReview && $alreadyReviewed)
                <a href="{{ route('products.show', $product) }}#reviews" class="px-3 py-2 border rounded-md text-sm">Lihat Ulasan</a>
                @else
                <span class="text-xs text-gray-500">Belum bisa memberi ulasan</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
