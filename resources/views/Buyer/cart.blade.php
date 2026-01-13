<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900">Cart</h1>
            <nav class="flex items-center gap-4 text-sm">
                <a href="{{ route('buyer.dashboard') }}" class="text-gray-700 hover:text-indigo-700">Home</a>
                <a href="{{ route('buyer.orders') }}" class="text-gray-700 hover:text-indigo-700">Orders</a>
                <a href="{{ route('buyer.messages') }}" class="text-gray-700 hover:text-indigo-700">Messages</a>
                <a href="{{ route('buyer.cart') }}" class="text-gray-700 hover:text-indigo-700">Cart <span class="ml-1 inline-block bg-indigo-50 text-xs px-2 rounded-full text-indigo-700" x-text="cartCount"></span></a>
                <a href="{{ route('buyer.profile') }}" class="text-gray-700 hover:text-indigo-700">Profile</a>
            </nav>
        </div>
    </x-slot>

    <main class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
            <div class="bg-white/95 rounded-lg shadow p-4 border-l-4 border-green-300">
            <h2 class="font-semibold text-green-700 text-lg">Keranjang Belanja</h2>

                    <div class="mt-4 space-y-3">
                        @php $subtotal = 0; @endphp
                        @forelse ($cart as $item)
                            <x-cart-item :item="$item" />
                            @php $subtotal += (optional($item->product)->price ?? optional($item->product)->harga ?? 0) * $item->quantity; @endphp
                        @empty
                            <div class="text-sm text-gray-600">Keranjang kosong.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <aside class="lg:col-span-1">
                <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-lg p-4 sticky top-6 shadow">
                    <div class="font-medium text-gray-700">Ringkasan</div>
                    <div class="mt-4 flex items-center justify-between">
                        <div class="text-sm text-gray-600">Subtotal</div>
                        <div id="cart-subtotal" class="text-xl font-extrabold text-indigo-700">Rp{{ number_format($subtotal,0,',','.') }}</div>
                    </div>

                    <div class="mt-4">
                        <a id="checkout-btn" href="#" class="block w-full text-center bg-gradient-to-r from-green-400 to-green-600 text-white px-4 py-3 rounded-lg shadow hover:from-green-500 hover:to-green-700 transition">Checkout</a>
                    </div>

                    <div class="mt-4 text-sm text-gray-500">Pengiriman dan pajak akan dihitung saat checkout.</div>
                </div>
            </aside>
        </div>
    </main>
</x-app-layout>
