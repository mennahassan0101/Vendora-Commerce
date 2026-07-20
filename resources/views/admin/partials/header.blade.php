<header class="bg-white border-b border-rose-100">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 h-20 flex items-center justify-between">
        <div class="flex items-center gap-10">
            <p class="font-display text-2xl">Vendora Admin</p>
            <nav class="flex items-center gap-6 text-sm font-medium text-ink/70">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-rose-600 transition-colors">Dashboard</a>
                <a href="{{ route('admin.products.index') }}" class="hover:text-rose-600 transition-colors">Products</a>
                <a href="{{ route('admin.categories.index') }}" class="hover:text-rose-600 transition-colors">Categories</a>
                <a href="{{ route('admin.orders.index') }}" class="hover:text-rose-600 transition-colors">Orders</a>

            </nav>
        </div>
        <div class="flex items-center gap-6">
                        @php
                $unreadNotifications = auth()->user()->unreadNotifications()->latest()->take(6)->get();
                $unreadCount = auth()->user()->unreadNotifications()->count();
            @endphp

            <details class="relative">
                <summary class="list-none cursor-pointer relative p-2 rounded-full hover:bg-rose-50 transition-colors">
                    <svg class="w-5 h-5 text-ink" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 0 0-4-5.7V5a2 2 0 1 0-4 0v.3A6 6 0 0 0 6 11v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0a3 3 0 1 1-6 0m6 0H9"/>
                    </svg>
                    @if ($unreadCount > 0)
                        <span class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 rounded-full bg-rose-600 text-white text-[10px] font-semibold flex items-center justify-center">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                    @endif
                </summary>

                <div class="absolute right-0 mt-2 w-80 bg-white rounded-2xl border border-rose-100 shadow-lg overflow-hidden z-50">
                    <div class="px-4 py-3 border-b border-rose-100 flex items-center justify-between">
                        <p class="text-sm font-semibold">Notifications</p>
                        @if ($unreadCount > 0)
                            <form action="{{ route('admin.notifications.read-all') }}" method="POST">
                                @csrf
                                <button class="text-xs text-rose-600 hover:text-rose-700 font-medium">Mark all read</button>
                            </form>
                        @endif
                    </div>

                    <div class="max-h-80 overflow-y-auto divide-y divide-rose-50">
                        @forelse ($unreadNotifications as $notification)
                            <a href="{{ $notification->data['url'] ?? '#' }}" class="block px-4 py-3 hover:bg-rose-50/60 transition-colors">
                                <p class="text-sm text-ink">{{ $notification->data['message'] ?? 'New notification' }}</p>
                                <p class="text-xs text-mauve mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                            </a>
                        @empty
                            <p class="px-4 py-6 text-sm text-mauve text-center">You're all caught up.</p>
                        @endforelse
                    </div>

                    <div class="px-4 py-3 border-t border-rose-100 text-center">
                        <a href="{{ route('admin.notifications.index') }}" class="text-xs font-semibold text-rose-600 hover:text-rose-700">View all</a>
                    </div>
                </div>
            </details>
            <span class="text-sm text-mauve">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="text-sm text-rose-600 hover:text-rose-700 font-semibold">Log out</button>
            </form>
        </div>
    </div>
</header>