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

    <main class="max-w-lg mx-auto p-4 sm:p-6 lg:p-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="font-semibold text-indigo-700 text-lg mb-2">Formulir Pendaftaran Seller</h2>
            <form method="POST" action="{{ route('buyer.seller.register') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm text-gray-600">Nama Toko</label>
                    <input name="shop_name" class="w-full mt-1 border-gray-200 rounded-md p-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm text-gray-600">Alamat Toko</label>
                    <input name="shop_address" class="w-full mt-1 border-gray-200 rounded-md p-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm text-gray-600">Deskripsi Toko</label>
                    <textarea name="shop_description" class="w-full mt-1 border-gray-200 rounded-md p-2"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm text-gray-600">No. HP/WA</label>
                    <input name="phone" class="w-full mt-1 border-gray-200 rounded-md p-2" required>
                </div>
                <div class="text-right">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md">Kirim Permohonan</button>
                </div>
            </form>
        </div>
    </main>
</x-app-layout>
