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
                        <img src="{{ auth()->user()->photo
                                ? asset('storage/profile/' . auth()->user()->photo)
                                : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}" class="rounded-circle object-fit-cover" width="40" height="40" alt="Profile">
                    </a>

                    <!-- MOBILE MENU -->
                    <button class="btn btn-light d-md-none">
                        <i class="bi bi-list"></i>
                    </button>
                </div>

            </div>
        </div>
    </header>





    <main class="container py-4">
        <div class="row g-4">

            <!-- CART LIST -->
            <div class="col-12 col-lg-8">
                <div class="card shadow border-start border-success border-4">
                    <div class="card-body">
                        <h5 class="fw-semibold text-success mb-3">Keranjang Belanja</h5>

                        <div class="mt-3" id="cart-list">
                            @php $subtotal = 0; @endphp

                            @forelse ($cart as $item)
                            <x-cart-item :item="$item" />
                            @php
                            $subtotal += (optional($item->product)->price ?? optional($item->product)->harga ?? 0) * $item->quantity;
                            @endphp
                            @empty
                            <div class="text-muted small">Keranjang kosong.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- SUMMARY -->
            <div class="col-12 col-lg-4">
                <div class="card shadow position-sticky top-0">
                    <div class="card-body" style="background: linear-gradient(135deg, #eef2ff, #e0f2fe); border-radius: .5rem">

                        <div class="fw-semibold text-secondary">Ringkasan</div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <span class="text-muted small">Subtotal</span>
                            <span id="cart-subtotal" class="fs-4 fw-bold text-primary">
                                Rp{{ number_format($subtotal,0,',','.') }}
                            </span>
                        </div>

                        <div class="mt-4">
                            <a id="checkout-btn" href="{{ route('buyer.cart.checkout.show') }}" class="btn btn-success w-100 py-3 fw-semibold shadow-sm">
                                Checkout
                            </a>
                        </div>

                        <div class="mt-3 text-muted small">
                            Pengiriman dan pajak akan dihitung saat checkout.
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </main>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrf = '{{ csrf_token() }}';

            function formatCurrency(value) {
                return 'Rp' + Number(value).toLocaleString('id-ID');
            }

            function setCartCount(count) {
                document.querySelectorAll('[x-text="cartCount"]').forEach(el => el.textContent = count);
            }

            function handleResponseErrors(err) {
                if (err.json) {
                    return err.json().then(data => {
                        alert(data.message || 'Terjadi kesalahan');
                    });
                }
                alert('Terjadi kesalahan jaringan');
            }

            function updateQuantity(itemId, newQty, container) {
                fetch(`/buyer/cart/${itemId}`, {
                        method: 'PATCH'
                        , headers: {
                            'Content-Type': 'application/json'
                            , 'X-CSRF-TOKEN': csrf
                            , 'Accept': 'application/json'
                        }
                        , body: JSON.stringify({
                            quantity: newQty
                        })
                    })
                    .then(resp => {
                        if (!resp.ok) throw resp;
                        return resp.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // update quantity and totals in DOM
                            container.querySelector('.qty-number').textContent = data.item_quantity;
                            const itemTotalEl = container.querySelector('.item-total');
                            itemTotalEl.textContent = formatCurrency(data.item_total);
                            itemTotalEl.setAttribute('data-price', data.item_total / data.item_quantity || 0);

                            const subtotalEl = document.getElementById('cart-subtotal');
                            if (subtotalEl) subtotalEl.textContent = formatCurrency(data.cart_subtotal);

                            setCartCount(data.cart_count);
                        } else {
                            alert(data.message || 'Gagal memperbarui jumlah');
                        }
                    }).catch(handleResponseErrors);
            }

            function removeItem(itemId, container) {
                fetch(`/buyer/cart/${itemId}`, {
                        method: 'DELETE'
                        , headers: {
                            'X-CSRF-TOKEN': csrf
                            , 'Accept': 'application/json'
                        }
                    })
                    .then(resp => {
                        if (!resp.ok) throw resp;
                        return resp.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // remove element from DOM
                            container.remove();
                            const subtotalEl = document.getElementById('cart-subtotal');
                            if (subtotalEl) subtotalEl.textContent = formatCurrency(data.cart_subtotal);
                            setCartCount(data.cart_count);

                            // if cart is empty, show message
                            const list = document.querySelector('.mt-4.space-y-3');
                            if (list && list.children.length === 0) {
                                list.innerHTML = '<div class="text-sm text-gray-600">Keranjang kosong.</div>';
                            }
                        } else {
                            alert('Gagal menghapus item');
                        }
                    }).catch(handleResponseErrors);
            }

            // delegate events for performance and future dynamic items
            document.addEventListener('click', function(e) {
                const inc = e.target.closest('.qty-increase');
                const dec = e.target.closest('.qty-decrease');
                const rem = e.target.closest('.remove-from-cart');

                if (inc) {
                    const itemId = inc.dataset.itemId;
                    const container = document.querySelector(`[data-item-id="${itemId}"]`);
                    if (!container) return;
                    const current = parseInt(container.querySelector('.qty-number').textContent || '0');
                    const newQty = current + 1;
                    updateQuantity(itemId, newQty, container);
                }

                if (dec) {
                    const itemId = dec.dataset.itemId;
                    const container = document.querySelector(`[data-item-id="${itemId}"]`);
                    if (!container) return;
                    const current = parseInt(container.querySelector('.qty-number').textContent || '0');
                    const newQty = current - 1;
                    if (newQty < 1) {
                        if (confirm('Jumlah menjadi 0. Hapus item dari keranjang?')) {
                            removeItem(itemId, container);
                        }
                        return;
                    }
                    updateQuantity(itemId, newQty, container);
                }

                if (rem) {
                    const itemId = rem.dataset.itemId;
                    const container = document.querySelector(`[data-item-id="${itemId}"]`);
                    if (!container) return;
                    if (confirm('Yakin ingin menghapus item ini dari keranjang?')) {
                        removeItem(itemId, container);
                    }
                }
            });
        });

    </script>
</x-app-layout>
