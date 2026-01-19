<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold text-gray-900">Permohonan Seller</h1>
    </x-slot>
    <main class="max-w-5xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="font-semibold text-indigo-700 text-lg mb-2">Daftar Permohonan Seller</h2>
            @if(session('success'))
                <div class="mb-4 text-green-600">{{ session('success') }}</div>
            @endif
            <table class="min-w-full text-sm border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2 border">User</th>
                        <th class="p-2 border">Nama Toko</th>
                        <th class="p-2 border">Alamat</th>
                        <th class="p-2 border">No HP</th>
                        <th class="p-2 border">Status</th>
                        <th class="p-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $req)
                        <tr>
                            <td class="border p-2">{{ $req->user->name ?? '-' }}</td>
                            <td class="border p-2">{{ $req->shop_name }}</td>
                            <td class="border p-2">{{ $req->shop_address }}</td>
                            <td class="border p-2">{{ $req->phone }}</td>
                            <td class="border p-2">{{ ucfirst($req->status) }}</td>
                            <td class="border p-2">
                                @if($req->status === 'pending')
                                    <form method="POST" action="{{ route('admin.seller-requests.approve', $req->id) }}" class="inline">
                                        @csrf
                                        <button class="bg-green-600 text-white px-2 py-1 rounded" onclick="return confirm('Setujui permohonan ini?')">Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.seller-requests.reject', $req->id) }}" class="inline ml-2">
                                        @csrf
                                        <button class="bg-red-600 text-white px-2 py-1 rounded" onclick="return confirm('Tolak permohonan ini?')">Reject</button>
                                    </form>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-gray-500 p-4">Belum ada permohonan</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">{{ $requests->links() }}</div>
        </div>
    </main>
</x-app-layout>
