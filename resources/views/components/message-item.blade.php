@props(['message'])

<li class="p-3 border rounded-md flex items-start gap-3 transition hover:shadow-md hover:bg-indigo-50 reveal">
    <div class="flex-1">
        <div class="flex items-center justify-between">
            <div class="font-medium text-indigo-700">{{ $message->from }}</div>
            <div class="text-xs text-gray-500">{{ $message->created_at->diffForHumans() }}</div>
        </div>
        <div class="text-sm text-gray-600 mt-1">{{ Str::limit($message->body, 140) }}</div>
    </div>

    <div class="flex-shrink-0 ml-2 flex flex-col gap-2 items-end">
        @if (!$message->is_read)
            <span class="text-xs bg-indigo-100 text-indigo-800 px-2 py-1 rounded">Baru</span>
        @endif
        <a href="#" class="text-xs text-indigo-600">Buka</a>
    </div>
</li>
