<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900">Profile</h1>
            <nav class="text-sm text-gray-600">
                <a href="{{ route('buyer.dashboard') }}" class="hover:underline">Dashboard</a>
            </nav>
        </div>
    </x-slot>

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
