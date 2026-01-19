<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold text-gray-900">Daftar Menjadi Seller</h1>
    </x-slot>
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
