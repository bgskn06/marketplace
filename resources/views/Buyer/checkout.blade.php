<x-app-layout>
    <header class="border-bottom bg-white">
        <div class="container-fluid container-xxl">
            <div class="d-flex align-items-center justify-content-between" style="height:64px">

                <!-- LEFT -->
                <div class="d-flex align-items-center gap-4 flex-grow-1">

                    <!-- LOGO -->
                    <a href="#" class="d-flex align-items-center gap-2 text-decoration-none">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-success">
                            <path d="M9 16v-8l3 5l3 -5v8"></path>
                            <path d="M19.875 6.27a2.225 2.225 0 0 1 1.125 1.948v7.284
              c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27
              a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27
              a2.225 2.225 0 0 1 -1.158 -1.948v-7.285
              c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98
              a2.33 2.33 0 0 1 2.25 0l6.75 3.98"></path>
                        </svg>
                        <span class="fs-5 fw-medium text-dark">
                            Marketplace <span class="fw-bold">UDB</span>
                        </span>
                    </a>
                </div>

                <!-- RIGHT -->
                <div class="d-flex align-items-center gap-3">

                    <!-- NAV -->
                    <nav class="d-none d-md-block">
                        <ul class="nav align-items-center gap-2">
                            <li class="nav-item">
                                <a href="{{ route('buyer.dashboard') }}" class="nav-link active bg-success bg-opacity-10 text-success rounded px-3">Home</a>

                            </li>
                            <li class="nav-item">
                                <a href="{{ $buyerOrdersUrl }}" class="nav-link text-muted">Orders</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('buyer.messages') }}" class="nav-link text-muted">Messages</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('buyer.cart') }}" class="nav-link text-muted">Cart <span class="ml-1 inline-block bg-indigo-50 text-xs px-2 rounded-full text-indigo-700" x-text="cartCount"></span></a>
                            </li>
                        </ul>
                    </nav>

                    <!-- DIVIDER -->
                    <div class="vr d-none d-md-block"></div>

                    <!-- AVATAR -->
                    <a href="{{ route('buyer.profile') }}">
                        <img src="https://images.unsplash.com/photo-1600486913747-55e5470d6f40" class="rounded-circle object-fit-cover" width="40" height="40" alt="Profile">
                    </a>

                    <!-- MOBILE MENU -->
                    <button class="btn btn-light d-md-none">
                        <i class="bi bi-list"></i>
                    </button>
                </div>

            </div>
        </div>
    </header>


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

                        <div class="mt-4">
                            <h3 class="font-medium">Metode Pembayaran</h3>
                            @foreach($paymentOptions as $key => $label)
                            <label class="flex items-center gap-3 mt-2">
                                <input type="radio" name="payment_method" value="{{ $key }}" {{ $loop->first ? 'checked' : '' }}>
                                <span class="text-sm">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>

                        <div class="mt-6">
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
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('checkout-form');
            const placeBtn = document.getElementById('place-order') || document.getElementById('place-order-summary');
            const csrf = '{{ csrf_token() }}';
            const subtotal = Number({
                {
                    $subtotal
                }
            });

            function formatCurrency(value) {
                return 'Rp' + Number(value).toLocaleString('id-ID');
            }

            function recalc() {
                const selected = form.querySelector('input[name="shipping"]:checked');
                const ship = Number((selected && selected.dataset ? selected.dataset.price : 0) || 0);
                document.getElementById('summary-shipping').textContent = formatCurrency(ship);
                document.getElementById('summary-total').textContent = formatCurrency(subtotal + ship);
            }

            form.addEventListener('change', function(e) {
                if (e.target.name === 'shipping') recalc();
            });

            function doPlaceOrder(btn) {
                const button = btn || placeBtn;
                if (!button) {
                    alert('Tombol checkout tidak ditemukan');
                    return;
                }
                button.disabled = true;
                const originalText = button.textContent;
                button.textContent = 'Processing...';

                const formData = new FormData(form);
                // include shipping price for convenience
                const shippingInput = form.querySelector('input[name="shipping"]:checked');
                formData.append('shipping_price', (shippingInput && shippingInput.dataset ? shippingInput.dataset.price : 0) || 0);

                fetch("{{ route('buyer.cart.checkout') }}", {
                        method: 'POST'
                        , headers: {
                            'X-CSRF-TOKEN': csrf
                            , 'Accept': 'application/json'
                        }
                        , body: formData
                    })
                    .then(resp => resp.json())
                    .then(data => {
                        if (data.success) {
                            // use redirect provided by server, otherwise fallback to shared URL
                            window.location.href = data.redirect || '{{ $buyerOrdersUrl }}';
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

            const summaryBtn = document.getElementById('place-order-summary');
            if (summaryBtn) {
                summaryBtn.addEventListener('click', function() {
                    doPlaceOrder(summaryBtn);
                    window.scrollTo({
                        top: 0
                        , behavior: 'smooth'
                    });
                });
            }

            const mainBtn = document.getElementById('place-order');
            if (mainBtn) {
                mainBtn.addEventListener('click', function() {
                    doPlaceOrder(mainBtn);
                });
            }

            recalc();
        });

    </script>
</x-app-layout>
