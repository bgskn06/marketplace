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


    <main class="container my-4">

        @if(auth()->check())
        <div class="card shadow border-start border-4 border-primary-subtle">
            <div class="card-body">

                {{-- HEADER --}}
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h5 class="fw-semibold text-primary mb-1">Informasi Akun</h5>
                        <p class="text-muted mb-0">Kelola data akun Anda di sini.</p>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">
                            Logout
                        </button>
                    </form>
                </div>

                {{-- FORM --}}
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="row g-3">

                    @csrf
                    @method('PATCH')

                    {{-- PHOTO --}}
                    <div class="col-12 d-flex align-items-center gap-3">
                        <img src="{{ auth()->user()->photo
                            ? asset('storage/profile/'.auth()->user()->photo)
                            : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name) }}" class="rounded-circle object-fit-cover" width="80" height="80" alt="Profile Photo">

                        <div>
                            <label class="form-label mb-1">Foto Profil</label>
                            <input type="file" name="photo" accept="image/*" class="form-control form-control-sm">
                            <div class="form-text">PNG / JPG maks 2MB</div>
                        </div>
                    </div>

                    {{-- NAME --}}
                    <div class="col-md-6">
                        <label class="form-label">Nama</label>
                        <input name="name" value="{{ old('name', optional($user)->name) }}" class="form-control" required>
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    {{-- EMAIL --}}
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input name="email" value="{{ old('email', optional($user)->email) }}" class="form-control" required>
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    {{-- ADDRESS --}}
                    <div class="col-12">
                        <label class="form-label">Alamat</label>
                        <input name="address" value="{{ old('address', optional($user)->address ?? '') }}" class="form-control">
                    </div>

                    {{-- ACTIONS --}}
                    <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            Simpan
                        </button>

                        <a href="{{ route('seller.register') }}" class="btn btn-outline-primary">
                            Daftar Seller
                        </a>
                    </div>

                </form>
            </div>
        </div>

        @else
        {{-- LOGIN BOX --}}
        <div class="card shadow border-start border-4 border-primary-subtle mx-auto" style="max-width:420px">
            <div class="card-body">

                <h5 class="fw-semibold text-primary mb-1">Masuk ke Akun Anda</h5>
                <p class="text-muted mb-3">Masuk untuk melihat profil dan pesanan Anda.</p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input name="email" type="email" value="{{ old('email') }}" class="form-control" required>
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input name="password" type="password" class="form-control" required>
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">
                                Ingat saya
                            </label>
                        </div>

                        <a href="{{ route('password.request') }}" class="text-decoration-none">
                            Lupa password?
                        </a>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            Masuk
                        </button>
                    </div>
                </form>

                <p class="text-muted mt-3 mb-0">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-primary fw-medium text-decoration-none">
                        Daftar sekarang
                    </a>
                </p>
            </div>
        </div>
        @endif

    </main>


</x-app-layout>
