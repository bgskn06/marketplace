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

                    <!-- SEARCH -->
                    <form method="GET" action="{{ route('buyer.search') }}" class="d-none d-sm-block position-relative">

                        <div class="flex-1 relative">
                            <input name="q" value="{{ request('q') }}" type="search" placeholder="Cari produk, seller, kategori..." class="form-control rounded-pill ps-4 pe-5" style="width:260px" />
                        </div>
                        <button type="submit" class="btn position-absolute top-50 end-0 translate-middle-y me-2 p-1">
                            <i class="bi bi-search text-muted"></i>
                        </button>
                    </form>
                </div>

                <!-- RIGHT -->
                <div class="d-flex align-items-center gap-3">

                    <!-- NAV -->
                    <nav class="d-none d-md-block">
                        <ul class="nav align-items-center gap-2">
                            <li class="nav-item">
                                <x-nav-link href="{{ route('buyer.dashboard') }}" :active="request()->routeIs('buyer.dashboard')">
                                    Home
                                </x-nav-link>
                            </li>

                            <li class="nav-item">
                                <x-nav-link href="{{ route('buyer.orders') }}" :active="request()->routeIs('buyer.orders')">
                                    Orders
                                </x-nav-link>
                            </li>

                            <li class="nav-item">
                                <x-nav-link href="{{ route('buyer.messages') }}" :active="request()->routeIs('buyer.messages')">
                                    Messages
                                </x-nav-link>
                            </li>

                            <li class="nav-item">
                                <x-nav-link href="{{ route('buyer.cart') }}" :active="request()->routeIs('buyer.cart')">
                                    Cart
                                </x-nav-link>
                            </li>
                        </ul>

                    </nav>

                    <!-- DIVIDER -->
                    <div class="vr d-none d-md-block"></div>

                    <!-- AVATAR -->
                    <a href="{{ route('buyer.profile') }}">
                        <img
                            src="{{ auth()->user()->photo
                                ? asset('storage/profile/' . auth()->user()->photo)
                                : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                            class="rounded-circle object-fit-cover"
                            width="40"
                            height="40"
                            alt="Profile">
                    </a>

                    <!-- MOBILE MENU -->
                    <button class="btn btn-light d-md-none">
                        <i class="bi bi-list"></i>
                    </button>
                </div>

            </div>
        </div>
    </header>




    <main class="container my-4 my-lg-5">
        <div class="card shadow-sm">
            <div class="card-body p-4 p-lg-5">

                <div class="row g-4">

                    <!-- LEFT -->
                    <section class="col-12 col-lg-8">
                        <h5 class="fw-semibold mb-4">Alamat Pengiriman</h5>

                        <form id="checkout-form">

                            <div class="mb-3">
                                <label class="form-label">Nama Penerima</label>
                                <input type="text" name="recipient_name" class="form-control" value="{{ auth()->user()->name ?? '' }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea name="address" class="form-control" rows="3" required>{{ old('address') }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Catatan (opsional)</label>
                                <input type="text" name="note" class="form-control" placeholder="Contoh: menitipkan di resepsionis">
                            </div>

                            <!-- SHIPPING -->
                            <div class="mb-4">
                                <h6 class="fw-semibold mb-3">Opsi Pengiriman</h6>

                                @foreach($shippingOptions as $key => $opt)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="shipping" value="{{ $key }}" data-price="{{ $opt[1] }}" {{ $loop->first ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                        {{ $opt[0] }} —
                                        <strong>Rp{{ number_format($opt[1],0,',','.') }}</strong>
                                    </label>
                                </div>
                                @endforeach
                            </div>

                            <!-- PAYMENT -->
                            <div class="mb-4">
                                <h6 class="fw-semibold mb-3">Metode Pembayaran</h6>

                                @foreach($paymentOptions as $key => $label)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" value="{{ $key }}" {{ $loop->first ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                        {{ $label }}
                                    </label>
                                </div>
                                @endforeach
                            </div>

                            <a href="{{ route('buyer.cart') }}" class="btn btn-outline-success w-100">
                                Kembali ke Keranjang
                            </a>

                        </form>
                    </section>

                    <!-- RIGHT -->
                    <aside class="col-12 col-lg-4">
                        <div class="card bg-light sticky-top" style="top: 90px">
                            <div class="card-body">

                                <h6 class="fw-semibold mb-3">Ringkasan Pesanan</h6>

                                <div class="mb-3">
                                    @foreach($items as $item)
                                    <div class="d-flex justify-content-between small mb-2">
                                        <div>
                                            {{ optional($item->product)->name ?? 'Produk' }}
                                            × {{ $item->quantity }}
                                        </div>
                                        <div>
                                            Rp{{ number_format((optional($item->product)->price ?? optional($item->product)->harga ?? 0) * $item->quantity,0,',','.') }}
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between small mb-2">
                                    <span>Subtotal</span>
                                    <span id="summary-subtotal">
                                        Rp{{ number_format($subtotal,0,',','.') }}
                                    </span>
                                </div>

                                <div class="d-flex justify-content-between small mb-2">
                                    <span>Ongkos Kirim</span>
                                    <span id="summary-shipping">
                                        Rp{{ number_format(array_values($shippingOptions)[0][1],0,',','.') }}
                                    </span>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between fw-bold text-success fs-5 mb-3">
                                    <span>Total</span>
                                    <span id="summary-total">
                                        Rp{{ number_format($subtotal + array_values($shippingOptions)[0][1],0,',','.') }}
                                    </span>
                                </div>

                                <button id="place-order-summary" type="button" class="btn btn-success w-100 py-2">
                                    Checkout
                                </button>

                            </div>
                        </div>
                    </aside>

                </div>

            </div>
        </div>
    </main>



    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const form = document.getElementById('checkout-form');
            const checkoutBtn = document.getElementById('place-order-summary');
            const csrf = '{{ csrf_token() }}';

            const subtotal = Number(@json($subtotal));

            function formatCurrency(value) {
                return 'Rp' + Number(value).toLocaleString('id-ID');
            }

            function getShippingPrice() {
                const selected = form.querySelector('input[name="shipping"]:checked');
                return selected ? Number(selected.dataset.price) : 0;
            }

            function recalcSummary() {
                const shipping = getShippingPrice();
                document.getElementById('summary-shipping').textContent = formatCurrency(shipping);
                document.getElementById('summary-total').textContent = formatCurrency(subtotal + shipping);
            }

            // Update total when shipping changed
            form.addEventListener('change', function(e) {
                if (e.target.name === 'shipping') {
                    recalcSummary();
                }
            });

            checkoutBtn.addEventListener('click', function() {

            const recipient = form.querySelector('[name="recipient_name"]');
            const address = form.querySelector('[name="address"]');

            if (!recipient.value.trim() || !address.value.trim()) {
            alert('Nama penerima dan alamat wajib diisi');
            return;
            }

            checkoutBtn.disabled = true;
            const originalText = checkoutBtn.textContent;
            checkoutBtn.textContent = 'Processing...';

            const formData = new FormData(form);
            formData.append('shipping_price', getShippingPrice());

            fetch("{{ route('buyer.cart.checkout') }}", {
            method: 'POST',
            headers: {
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json'
            },
            body: formData
            })
            .then(async response => {
            const data = await response.json();
            if (!response.ok) throw data;
            return data;
            })
            .then(data => {
            if (data.success) {
            window.location.href = data.redirect ?? "{{ $buyerOrdersUrl }}";
            } else {
            alert(data.message || 'Checkout gagal');
            }
            })
            .catch(err => {
            console.error(err);
            alert(err.message || 'Terjadi kesalahan saat checkout');
            })
            .finally(() => {
            checkoutBtn.disabled = false;
            checkoutBtn.textContent = originalText;
            });
            });



            // Init
            recalcSummary();
        });

    </script>

</x-app-layout>
