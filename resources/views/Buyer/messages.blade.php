<x-app-layout>
    <header class="border-bottom bg-white">
        <div class="container-fluid container-xxl">
            <div class="d-flex align-items-center justify-content-between" style="height:64px">

                <!-- LEFT -->
                <div class="d-flex align-items-center gap-4 flex-grow-1">

                    <!-- LOGO -->
                    <a href="#" class="d-flex align-items-center gap-2 text-decoration-none">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-success">
                            <path d="M9 16v-8l3 5l3 -5v8"></path>
                            <path d="M19.875 6.27a2.225 2.225 0 0 1 1.125 1.948v7.284
              c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27
              a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27
              a2.225 2.225 0 0 1 -1.158 -1.948v-7.285
              c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98
              a2.33 2.33 0 0 1 2.25 0l6.75 3.98"></path>
                        </svg>
                        <span class="fs-5 fw-medium text-dark">
                            MARKETIFY
                        </span>
                    </a>
                </div>

                <!-- RIGHT -->
                <div class="d-flex align-items-center gap-3">

                    <!-- NAV -->
                    <nav class="d-none d-md-block">
                        <ul class="nav align-items-center gap-2">

                            {{-- HOME TAB --}}
                            <li class="nav-item">
                                <a href="{{ route('buyer.dashboard') }}"
                                    class="nav-link px-3 rounded-pill fw-medium transition-all   {{ request()->routeIs('buyer.dashboard')
                                        ? 'bg-success bg-opacity-10 text-success fw-bold shadow-sm'
                                        : 'text-muted hover-text-success' }}">
                                    Home
                                </a>
                            </li>

                            {{-- ORDERS TAB --}}
                            <li class="nav-item">
                                <a href="{{ $buyerOrdersUrl }}"
                                    class="nav-link px-3 rounded-pill fw-medium transition-all 
               {{ request()->routeIs('buyer.orders*')
                   ? 'bg-success bg-opacity-10 text-success fw-bold shadow-sm'
                   : 'text-muted hover-text-success' }}">
                                    Orders
                                </a>
                            </li>

                            {{-- MESSAGES TAB --}}
                            <li class="nav-item">
                                <a href="{{ route('buyer.messages') }}"
                                    class="nav-link px-3 rounded-pill fw-medium transition-all 
               {{ request()->routeIs('buyer.messages*')
                   ? 'bg-success bg-opacity-10 text-success fw-bold shadow-sm'
                   : 'text-muted hover-text-success' }}">
                                    Messages
                                </a>
                            </li>

                            {{-- CART TAB --}}
                            <li class="nav-item">
                                <a href="{{ route('buyer.cart') }}"
                                    class="nav-link px-3 rounded-pill fw-medium transition-all d-flex align-items-center gap-2
               {{ request()->routeIs('buyer.cart*')
                   ? 'bg-success bg-opacity-10 text-success fw-bold shadow-sm'
                   : 'text-muted hover-text-success' }}">
                                    Cart
                                    {{-- Badge Cart --}}
                                    <span
                                        class="badge rounded-pill 
                   {{ request()->routeIs('buyer.cart*') ? 'bg-success text-light' : 'bg-light text-secondary border' }}"
                                        x-text="cartCount" style="font-size: 0.75rem;">
                                    </span>
                                </a>
                            </li>

                        </ul>
                    </nav>

                    <!-- DIVIDER -->
                    <div class="vr d-none d-md-block"></div>

                    <!-- AVATAR -->
                    <a href="{{ route('buyer.profile') }}">
                        <img src="https://images.unsplash.com/photo-1600486913747-55e5470d6f40"
                            class="rounded-circle object-fit-cover" width="40" height="40" alt="Profile">
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
        <div class="bg-white/95 rounded-lg shadow p-4 border-l-4 border-pink-300">
            <h2 class="font-semibold text-pink-600 text-lg">Kotak Masuk</h2>

            <div class="mt-2">
                @livewire('seller-chat')
            </div>
        </div>
    </main>
</x-app-layout>
