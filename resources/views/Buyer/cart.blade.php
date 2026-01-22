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
                        <a id="checkout-btn" href="{{ route('buyer.cart.checkout.show') }}" class="block w-full text-center bg-gradient-to-r from-green-400 to-green-600 text-white px-4 py-3 rounded-lg shadow hover:from-green-500 hover:to-green-700 transition">Checkout</a>
                    </div>

                    <div class="mt-4 text-sm text-gray-500">Pengiriman dan pajak akan dihitung saat checkout.</div>
                </div>
            </aside>
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
