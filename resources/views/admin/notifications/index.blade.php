<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notifications — Vendora Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-rose-50/30 text-ink font-sans antialiased">

    @include('admin.partials.header')

    <main class="max-w-3xl mx-auto px-6 lg:px-8 py-10">
        <h1 class="font-display text-3xl mb-8">Notifications</h1>

        <div class="bg-white rounded-2xl border border-rose-100 divide-y divide-rose-50">
            @forelse ($notifications as $notification)
                <div class="px-6 py-4 flex items-start justify-between gap-4 {{ $notification->read_at ? '' : 'bg-rose-50/40' }}">
                    <a href="{{ $notification->data['url'] ?? '#' }}" class="flex-1">
                        <p class="text-sm text-ink">{{ $notification->data['message'] ?? 'Notification' }}</p>
                        <p class="text-xs text-mauve mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                    </a>
                    @unless ($notification->read_at)
                        <form action="{{ route('admin.notifications.read', $notification->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button class="text-xs text-rose-600 hover:text-rose-700 font-medium whitespace-nowrap">Mark read</button>
                        </form>
                    @endunless
                </div>
            @empty
                <p class="px-6 py-16 text-center text-mauve">No notifications yet.</p>
            @endforelse
        </div>

        <div class="mt-6">{{ $notifications->links() }}</div>
    </main>

</body>
</html>