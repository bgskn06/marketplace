<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900">Checkout</h1>
            <nav class="flex items-center gap-4 text-sm">
                <a href="{{ route('buyer.dashboard') }}" class="text-gray-700 hover:text-indigo-700">Home</a>
                <a href="{{ route('buyer.orders') }}" class="text-gray-700 hover:text-indigo-700">Orders</a>
                <a href="{{ route('buyer.cart') }}" class="text-gray-700 hover:text-indigo-700">Cart</a>
                <a href="{{ route('buyer.profile') }}" class="text-gray-700 hover:text-indigo-700">Profile</a>
            </nav>
        </div>
    </x-slot>

    <main class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <section class="md:col-span-2">
                    <h2 class="font-semibold text-lg">Alamat Pengiriman</h2>
                    <form id="checkout-form">
                        <div class="mt-3">
                            <label class="block text-sm text-gray-700">Nama Penerima</label>
                            <input type="text" name="recipient_name" class="w-full mt-1 border rounded px-3 py-2" value="{{ auth()->user()->name ?? '' }}" required>
                        </div>

                        <div class="mt-3">
                            <label class="block text-sm text-gray-700">Alamat</label>
                            <textarea name="address" class="w-full mt-1 border rounded px-3 py-2" rows="3" required>{{ old('address') }}</textarea>
                        </div>

                        <div class="mt-3">
                            <label class="block text-sm text-gray-700">Catatan (opsional)</label>
                            <input type="text" name="note" class="w-full mt-1 border rounded px-3 py-2" placeholder="Contoh: menitipkan di resepsionis">
                        </div>

                        <div class="mt-4">
                            <h3 class="font-medium">Opsi Pengiriman</h3>
                            @foreach($shippingOptions as $key => $opt)
                                <label class="flex items-center gap-3 mt-2">
                                    <input type="radio" name="shipping" value="{{ $key }}" data-price="{{ $opt[1] }}" {{ $loop->first ? 'checked' : '' }}>
                                    <span class="text-sm">{{ $opt[0] }} — <strong>Rp{{ number_format($opt[1],0,',','.') }}</strong></span>
                                </label>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            <button id="place-order" type="button" class="btn btn-success w-100">Place Order</button>

                            <a href="{{ route('buyer.cart') }}" class="btn btn-success w-100">Kembali ke Keranjang</a>

                        </div>
                    </form>
                </section>

                <aside class="md:col-span-1 bg-gray-50 p-4 rounded">
                    <h3 class="font-semibold">Ringkasan Pesanan</h3>
                    <div class="mt-4 space-y-3">
                        @foreach($items as $item)
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-700">{{ optional($item->product)->name ?? 'Produk' }} x {{ $item->quantity }}</div>
                                <div class="text-sm text-gray-700">Rp{{ number_format((optional($item->product)->price ?? optional($item->product)->harga ?? 0) * $item->quantity,0,',','.') }}</div>
                            </div>
                        @endforeach
                    </div>

                    <hr class="my-4">

                    <div class="flex items-center justify-between text-sm text-gray-700">
                        <div>Subtotal</div>
                        <div id="summary-subtotal">Rp{{ number_format($subtotal,0,',','.') }}</div>
                    </div>

                    <div class="flex items-center justify-between text-sm text-gray-700 mt-2">
                        <div>Ongkos Kirim</div>
                        <div id="summary-shipping">Rp{{ number_format(array_values($shippingOptions)[0][1],0,',','.') }}</div>
                    </div>

                    <div class="flex items-center justify-between text-lg font-semibold text-indigo-700 mt-4">
                        <div>Total</div>
                        <div id="summary-total">Rp{{ number_format($subtotal + array_values($shippingOptions)[0][1],0,',','.') }}</div>
                    </div>

                    <div class="mt-4">
                        <button id="place-order-summary" type="button" class="btn btn-success w-100">Checkout</button>
                    </div>
                </aside>
            </div>
        </div>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('checkout-form');
        const placeBtn = document.getElementById('place-order');
        const csrf = '{{ csrf_token() }}';
        const subtotal = Number({{ $subtotal }});

        function formatCurrency(value) {
            return 'Rp' + Number(value).toLocaleString('id-ID');
        }

        function recalc() {
            const selected = form.querySelector('input[name="shipping"]:checked');
            const ship = Number(selected?.dataset.price || 0);
            document.getElementById('summary-shipping').textContent = formatCurrency(ship);
            document.getElementById('summary-total').textContent = formatCurrency(subtotal + ship);
        }

        form.addEventListener('change', function (e) {
            if (e.target.name === 'shipping') recalc();
        });

        function doPlaceOrder(btn) {
            const button = btn || placeBtn;
            button.disabled = true;
            const originalText = button.textContent;
            button.textContent = 'Processing...';

            const formData = new FormData(form);
            // include shipping price for convenience
            const shippingInput = form.querySelector('input[name="shipping"]:checked');
            formData.append('shipping_price', shippingInput?.dataset.price || 0);

            fetch("{{ route('buyer.cart.checkout') }}", {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': csrf, 'Accept': 'application/json'},
                body: formData
            })
            .then(resp => resp.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect || '{{ route('buyer.orders') }}';
                } else {
                    alert(data.message || 'Checkout gagal');
                }
            })
            .catch(() => alert('Terjadi kesalahan'))
            .finally(() => {
                button.disabled = false;
                button.textContent = originalText || 'Place Order';
            });
        }

        placeBtn.addEventListener('click', function () {
            doPlaceOrder(placeBtn);
        });

        const summaryBtn = document.getElementById('place-order-summary');
        if (summaryBtn) {
            summaryBtn.addEventListener('click', function () {
                doPlaceOrder(summaryBtn);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }

        recalc();
    });
    </script>
</x-app-layout>
