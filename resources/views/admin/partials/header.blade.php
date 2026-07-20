<header class="bg-white border-b border-rose-100">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 h-20 flex items-center justify-between">
        <div class="flex items-center gap-10">
            <p class="font-display text-2xl">Vendora Admin</p>
            <nav class="flex items-center gap-6 text-sm font-medium text-ink/70">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-rose-600 transition-colors">Dashboard</a>
                <a href="{{ route('admin.products.index') }}" class="hover:text-rose-600 transition-colors">Products</a>
                <a href="{{ route('admin.categories.index') }}" class="hover:text-rose-600 transition-colors">Categories</a>

            </nav>
        </div>
        <div class="flex items-center gap-6">
            <span class="text-sm text-mauve">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="text-sm text-rose-600 hover:text-rose-700 font-semibold">Log out</button>
            </form>
        </div>
    </div>
</header>