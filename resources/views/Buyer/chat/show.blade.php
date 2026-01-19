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
            <h2 class="font-semibold text-gray-800">Chat dengan {{ ($conversation->sender_id == auth()->id()) ? ($conversation->receiver->name ?? 'User') : ($conversation->sender->name ?? 'User') }}</h2>

            <div class="mt-4 space-y-3 max-h-96 overflow-auto">
                @foreach($messages as $message)
                <div class="p-2 rounded {{ $message->user_id == auth()->id() ? 'bg-indigo-50 text-right' : 'bg-gray-100 text-left' }}">
                    <div class="text-sm text-gray-700">{{ $message->body }}</div>
                    <div class="text-xs text-gray-400 mt-1">{{ optional($message->created_at)->format('Y-m-d H:i') }}</div>
                </div>
                @endforeach
            </div>

            <form method="POST" action="{{ route('buyer.chat.message', $conversation) }}" class="mt-4">
                @csrf
                <div class="flex gap-2">
                    <input name="message" class="flex-1 border-gray-200 rounded-md p-2" placeholder="Tulis pesan..." />
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded-md">Kirim</button>
                </div>
            </form>
        </div>
    </main>
</x-app-layout>
