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
                                <a href="{{ route('buyer.orders') }}" class="nav-link text-muted">Orders</a>
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
        @if(auth()->check())
            <div class="bg-white/95 rounded-lg shadow p-6 border-l-4 border-indigo-300">
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="font-semibold text-indigo-700 text-lg">Informasi Akun</h2>
                        <p class="text-sm text-gray-600 mt-1">Kelola data akun Anda di sini.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="px-3 py-2 bg-red-600 text-white rounded-md">Logout</button>
                        </form>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-sm text-gray-600">Nama</label>
                        <input name="name" value="{{ old('name', optional($user)->name) }}" class="w-full mt-1 border-gray-200 rounded-md p-2" required>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600">Email</label>
                        <input name="email" value="{{ old('email', optional($user)->email) }}" class="w-full mt-1 border-gray-200 rounded-md p-2" required>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm text-gray-600">Alamat</label>
                        <input name="address" value="{{ old('address', optional($user)->address ?? '') }}" class="w-full mt-1 border-gray-200 rounded-md p-2">
                    </div>

                    <div class="sm:col-span-2 text-right">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md">Simpan</button>
                    </div>

                    <div class="sm:col-span-2 text-right">
                        <a href="{{ route('seller.register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md inline-block">Daftar Seller</a>
                    </div>
                </form>
            </div>
        @else
            <div class="bg-white/95 rounded-lg shadow p-6 border-l-4 border-indigo-300 max-w-md mx-auto">
                <h2 class="font-semibold text-indigo-700 text-lg">Masuk ke Akun Anda</h2>
                <p class="text-sm text-gray-600 mt-1">Masuk untuk melihat profil dan pesanan Anda.</p>

                <form method="POST" action="{{ route('login') }}" class="mt-4 space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm text-gray-600">Email</label>
                        <input name="email" value="{{ old('email') }}" type="email" required class="w-full mt-1 border-gray-200 rounded-md p-2">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600">Password</label>
                        <input name="password" type="password" required class="w-full mt-1 border-gray-200 rounded-md p-2">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="inline-flex items-center text-sm text-gray-600">
                            <input type="checkbox" name="remember" class="mr-2"> Ingat saya
                        </label>

                        <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:underline">Lupa password?</a>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md">Masuk</button>
                    </div>
                </form>

                <p class="mt-4 text-sm text-gray-600">Belum punya akun? <a href="{{ route('register') }}" class="text-indigo-600 hover:underline">Daftar sekarang</a></p>
            </div>
        @endif
    </main>
</x-app-layout>
