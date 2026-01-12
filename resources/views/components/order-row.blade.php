@props(['order'])

<div data-reveal class="reveal flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 p-4 rounded-lg bg-white shadow-sm hover:shadow-md transition">
    <div>
        <div class="font-medium text-gray-800">{{ $order->order_number ?? ('#INV-' . $order->id) }}</div>
        <div class="text-sm text-gray-500">Tanggal: {{ optional($order->created_at)->format('Y-m-d') }}</div>
    </div>
    <div class="text-sm text-gray-700">Total: <strong class="text-gray-900">Rp{{ number_format($order->total,0,',','.') }}</strong></div>
    <div class="mt-2 sm:mt-0">
        <span class="px-3 py-1 rounded-full text-sm {{ strtolower($order->status) === 'delivered' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">{{ ucfirst($order->status) }}</span>
    </div>
</div>
