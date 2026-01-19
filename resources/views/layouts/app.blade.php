<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Dashboard</title>

    <script src="https://code.iconify.design/3/3.1.1/iconify.min.js"></script>

    <link href="{{ asset('admin_assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    {{-- @if(!(auth()->check() && auth()->user()->role === 'buyer')) --}}
        <link href="{{ asset('admin_assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
    {{-- @endif --}}

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<body id="page-top">

    <div id="wrapper">

        @if(auth()->check() && auth()->user()->role === 'buyer')
            <div id="content-wrapper" class="min-h-screen bg-gray-100 w-full">
                <div id="content" class="py-6">
                    <div class="mx-auto px-4 sm:px-6 lg:px-8">
                        @isset($header)
                            <div class="mb-4">
                                <h1 class="text-2xl font-semibold text-gray-900">{{ $header }}</h1>
                            </div>
                        @endisset

                        <main>
                            {{ $slot }}
                        </main>
                    </div>
                </div>
            </div>
        @else

            {{-- Admin / Seller layout (original) --}}
            @include('layouts.sidebar')
            <div id="content-wrapper" class="d-flex flex-column">

                <div id="content">

                    @include('layouts.topbar')
                    <div class="container-fluid">

                        @isset($header)
                            <div class="d-sm-flex mb-4">
                                <h1 class="h3 mb-0 text-gray-800">{{ $header }}</h1>
                            </div>
                        @endisset

                        <main>
                            {{ $slot }}
                        </main>

                    </div>
                </div>
                @include('layouts.footer')
            </div>
        @endif

    </div>
    {{--  <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>  --}}

    {{--  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>  --}}

    <script src="{{ asset('admin_assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin_assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('admin_assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <script src="{{ asset('admin_assets/js/sb-admin-2.min.js') }}"></script>

    <script src="{{ asset('admin_assets/vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('admin_assets/js/demo/chart-pie-demo.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>


    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Only run cart script if cart elements exist
        const cartPage = document.querySelector('.mt-4.space-y-3');
        if (!cartPage) {
            console.debug('[Cart JS] Not on cart page, skipping cart event binding.');
            return;
        }
        const selector = "form[action='{{ route('buyer.cart.add') }}']";
        document.querySelectorAll(selector).forEach(form => {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                const btn = form.querySelector('button[type=\"submit\"]');
                if (btn) btn.disabled = true;
                const formData = new FormData(form);
                try {
                    const res = await fetch(form.action, {
                        method: form.method || 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    if (!res.ok) throw new Error('Network response was not ok');
                    const json = await res.json();
                    if (json.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Berhasil dimasukkan ke keranjang',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        if (typeof json.cart_count !== 'undefined') {
                            document.querySelectorAll('[x-text="cartCount"], .cart-count').forEach(el => {
                                el.textContent = json.cart_count;
                            });
                        }
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal menambahkan ke keranjang' });
                    }
                } catch (err) {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan saat menambahkan ke keranjang' });
                } finally {
                    if (btn) btn.disabled = false;
                }
            });
        });

        // Cart interactions: update quantity, remove, checkout
        const baseCartUrl = "{{ url('/buyer/cart') }}";

        function formatIDR(value) {
            return 'Rp' + new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(value);
        }

        function updateCartCount(count) {
            document.querySelectorAll('[x-text="cartCount"], .cart-count').forEach(el => {
                el.textContent = count;
            });
        }

        function bindCartEvents() {
            let found = false;
            document.querySelectorAll('.qty-increase').forEach(btn => {
                found = true;
                if (btn.dataset.bound) return;
                btn.dataset.bound = 1;
                btn.addEventListener('click', async function(e) {
                    e.preventDefault();
                    const itemId = this.dataset.itemId;
                    const wrapper = this.closest('[data-item-id]');
                    const qtyEl = wrapper.querySelector('.qty-number');
                    const current = parseInt(qtyEl.textContent || '0');
                    const newQty = current + 1;
                    this.disabled = true;
                    try {
                        const res = await fetch(`${baseCartUrl}/${itemId}`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ quantity: newQty })
                        });
                        const json = await res.json();
                        if (json.success) {
                            qtyEl.textContent = json.item_quantity;
                            wrapper.querySelector('.item-total').textContent = formatIDR(json.item_total);
                            document.getElementById('cart-subtotal')?.textContent = formatIDR(json.cart_subtotal);
                            if (typeof json.cart_count !== 'undefined') updateCartCount(json.cart_count);
                        } else {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: json.message || 'Gagal mengupdate jumlah' });
                        }
                    } catch (err) {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan saat mengupdate jumlah' });
                    } finally {
                        this.disabled = false;
                    }
                });
            });
            document.querySelectorAll('.qty-decrease').forEach(btn => {
                found = true;
                if (btn.dataset.bound) return;
                btn.dataset.bound = 1;
                btn.addEventListener('click', async function(e) {
                    e.preventDefault();
                    const itemId = this.dataset.itemId;
                    const wrapper = this.closest('[data-item-id]');
                    const qtyEl = wrapper.querySelector('.qty-number');
                    const current = parseInt(qtyEl.textContent || '0');
                    if (current <= 1) return;
                    const newQty = current - 1;
                    this.disabled = true;
                    try {
                        const res = await fetch(`${baseCartUrl}/${itemId}`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ quantity: newQty })
                        });
                        const json = await res.json();
                        if (json.success) {
                            qtyEl.textContent = json.item_quantity;
                            wrapper.querySelector('.item-total').textContent = formatIDR(json.item_total);
                            document.getElementById('cart-subtotal')?.textContent = formatIDR(json.cart_subtotal);
                            if (typeof json.cart_count !== 'undefined') updateCartCount(json.cart_count);
                        } else {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: json.message || 'Gagal mengupdate jumlah' });
                        }
                    } catch (err) {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan saat mengupdate jumlah' });
                    } finally {
                        this.disabled = false;
                    }
                });
            });
            document.querySelectorAll('.remove-from-cart').forEach(btn => {
                found = true;
                if (btn.dataset.bound) return;
                btn.dataset.bound = 1;
                btn.addEventListener('click', async function(e) {
                    e.preventDefault();
                    const itemId = this.dataset.itemId;
                    const wrapper = this.closest('[data-item-id]');
                    const confirm = await Swal.fire({
                        title: 'Hapus item?',
                        text: 'Item akan dihapus dari keranjang',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Hapus',
                        cancelButtonText: 'Batal'
                    });
                    if (!confirm.isConfirmed) return;
                    this.disabled = true;
                    try {
                        const res = await fetch(`${baseCartUrl}/${itemId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });
                        const json = await res.json();
                        if (json.success) {
                            wrapper.remove();
                            document.getElementById('cart-subtotal')?.textContent = formatIDR(json.cart_subtotal || 0);
                            if (typeof json.cart_count !== 'undefined') updateCartCount(json.cart_count);
                            if (document.querySelectorAll('[data-item-id]').length === 0) {
                                const container = document.querySelector('.mt-4.space-y-3');
                                if (container) {
                                    container.innerHTML = '<div class="text-sm text-gray-600">Keranjang kosong.</div>';
                                }
                            }
                            Swal.fire({ icon: 'success', title: 'Terhapus', text: 'Item dihapus dari keranjang', timer: 1200, showConfirmButton: false });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: json.message || 'Gagal menghapus item' });
                        }
                    } catch (err) {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan saat menghapus item' });
                    } finally {
                        this.disabled = false;
                    }
                });
            });
            const checkoutBtn = document.getElementById('checkout-btn');
            if (checkoutBtn && !checkoutBtn.dataset.bound) {
                found = true;
                checkoutBtn.dataset.bound = 1;
                checkoutBtn.addEventListener('click', async function(e) {
                    e.preventDefault();
                    this.disabled = true;
                    try {
                        const res = await fetch("{{ route('buyer.cart.checkout') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });
                        const json = await res.json();
                        if (json.success) {
                            Swal.fire({ icon: 'success', title: 'Checkout berhasil', text: 'Pesanan dibuat', timer: 1400, showConfirmButton: false });
                            setTimeout(() => {
                                if (json.redirect) window.location = json.redirect;
                                else location.reload();
                            }, 1400);
                        } else {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: json.message || 'Gagal checkout' });
                        }
                    } catch (err) {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan saat checkout' });
                    } finally {
                        this.disabled = false;
                    }
                });
            }
            if (!found) {
                console.error('[Cart JS] Tidak menemukan tombol qty-increase, qty-decrease, remove-from-cart, atau checkout-btn di halaman. Pastikan markup sudah benar.');
            }
        }
        // Initial bind
        bindCartEvents();
    });
    </script>


    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}",
                timer: 2000,
                showConfirmButton: false
            })
        </script>
    @endif


</body>

</html>
