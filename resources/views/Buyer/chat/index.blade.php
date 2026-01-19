<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900">Chat</h1>
            <nav class="flex items-center gap-4 text-sm">
                <a href="{{ route('buyer.dashboard') }}" class="text-gray-700 hover:text-indigo-700">Home</a>
                <a href="{{ route('buyer.orders') }}" class="text-gray-700 hover:text-indigo-700">Orders</a>
                <a href="{{ route('buyer.messages') }}" class="text-gray-700 hover:text-indigo-700">Messages</a>
                <a href="{{ route('buyer.cart') }}" class="text-gray-700 hover:text-indigo-700">Cart</a>
                <a href="{{ route('buyer.profile') }}" class="text-gray-700 hover:text-indigo-700">Profile</a>
            </nav>
        </div>
    </x-slot>

    <main class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="font-semibold text-gray-800">Percakapan</h2>

            <div class="mt-4 space-y-3">
                @forelse($conversations as $conv)
                @php $other = $conv->receiver_attribute ?? $conv->receiver ?? ($conv->sender_id == auth()->id() ? $conv->receiver : $conv->sender); @endphp
                <a href="{{ route('buyer.chat.show', $conv) }}" class="block p-3 border rounded hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="font-medium">{{ $other->name ?? 'User' }}</div>
                            <div class="text-xs text-gray-500">{{ optional($conv->latestMessage)->body }}</div>
                        </div>
                        <div class="text-xs text-gray-400">{{ optional($conv->latestMessage)->created_at?->format('Y-m-d H:i') }}</div>
                    </div>
                </a>
                @empty
                <div class="text-sm text-gray-600">Belum ada percakapan.</div>
                @endforelse
            </div>
        </div>
    </main>
</x-app-layout>
